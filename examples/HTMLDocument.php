<?php
/**
 * Class HTMLDocument
 *
 * @created      26.03.2021
 * @author       smiley <smiley@chillerlan.net>
 * @copyright    2021 smiley
 * @license      MIT
 */

namespace chillerlan\PrototypeDOMExamples;

use chillerlan\PrototypeDOM\Document;
use chillerlan\PrototypeDOM\Node\{Element, PrototypeHTMLElement};
use DOMException;

class HTMLDocument extends Document{

	/**
	 * @return string|null
	 */
	public function getTitle():?string{
		return $this->select(['head > title'])->item(0)->nodeValue ?? null;
	}

	/**
	 * @throws \DOMException
	 */
	public function setTitle(string $title):void{
		$currentTitle = $this->select(['head > title'])->item(0);

		if($currentTitle instanceof Element){
			$currentTitle->update($title);

			return;
		}

		$head         = $this->select(['head'])->item(0);
		$currentTitle = $this->newElement('title')->update($title);

		if(!$head){
			$html = $this->select(['html'])->first();

			if(!$html instanceof PrototypeHTMLElement){
				throw new DOMException('html header missing');
			}

			$head = $this->newElement('head');
			$html->insert_top($head);
		}

		$head->insert($currentTitle);
	}

/*
	public function testSetTitle():void{
		$this::assertSame('Prototype DOM Test', $this->dom->getTitle());

		$this->dom->setTitle('foo');
		$this::assertSame('foo', $this->dom->getTitle());

		$this->dom->select(['head > title'])->item(0)->removeNode();
		$this::assertNull($this->dom->getTitle());

		$this->dom->setTitle('bar');
		$this::assertSame('bar', $this->dom->getTitle());

		$this->dom        = new Document('<html><body></body></html>');
		$this->dom->setTitle('nohead');
		$this::assertSame('nohead', $this->dom->getTitle());
	}

	public function testSetTitleInvalidHTMLException():void{
		$this->expectException(DOMException::class);
		$this->expectExceptionMessage('html header missing');

		$d = new Document;
		$d->setTitle('nope');
	}
*/

}
