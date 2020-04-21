<?php
/**
 * Class DocumentTest
 *
 * @filesource   DocumentTest.php
 * @created      05.05.2017
 * @package      chillerlan\PrototypeDOMTest
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2017 Smiley
 * @license      MIT
 */

namespace chillerlan\PrototypeDOMTest;

use DOMDocument, DOMException, DOMNodeList;
use chillerlan\PrototypeDOM\{Document, NodeList};

class DocumentTest extends TestAbstract{

	public function testInstance(){
		$this->assertInstanceOf(Document::class, $this->dom);
		$this->assertInstanceOf(DOMDocument::class, $this->dom);
	}

	public function testLoadDocument(){
		$this->dom = new DOMDocument;
		$this->dom->load(__DIR__.'/test.html');
		$DOMNodeList = $this->dom->getElementsByTagName('ul');
		$this->assertInstanceOf(DOMNodeList::class, $DOMNodeList);

		// from \DOMNodeList
		$NodeList = (new Document($DOMNodeList))->select(['#list-of-apples']);
		$this->assertInstanceOf(NodeList::class, $NodeList);
		$this->assertSame('golden-delicious', $NodeList->first()->childElements()->first()->getID());

		// from \chillerlan\PrototypeDOM\NodeList
		$this->assertSame('golden-delicious', (new Document($NodeList))->getElementById('list-of-apples')->childElements()->first()->getID());

		// from xml string
		$this->dom = new Document(file_get_contents(__DIR__.'/../phpunit.xml'), true);
		$this->dom->select(['directory'])->each(function($e, $i){
			$this->assertSame('.php', $e->getAttribute('suffix'));
		});
	}

	public function testLoadDocumentException(){
		$this->expectException(DOMException::class);
		$this->expectExceptionMessage('invalid document content');
		new Document([]);
	}

	public function testLoadDocumentFile(){
		// html
		$this->dom = new Document(__DIR__.'/test.html');
		$this->assertSame('Golden Delicious', $this->dom->getElementById('golden-delicious')->nodeValue);

		// xml
		$this->dom = new Document(__DIR__.'/../phpunit.xml', true);
		$this->dom->select(['directory'])->each(function($e, $i){
			$this->assertSame('.php', $e->getAttribute('suffix'));
		});
	}

	public function testMagicTitle(){
		$this->assertSame('Prototype DOM Test', $this->dom->getTitle());

		$this->dom->setTitle('foo');
		$this->assertSame('foo', $this->dom->getTitle());

		$this->dom->select(['head > title'])->offsetGet(0)->remove();
		$this->assertNull($this->dom->getTitle());

		$this->dom->setTitle('bar');
		$this->assertSame('bar', $this->dom->getTitle());

		$this->dom        = new Document('<html><body></body></html>');
		$this->dom->setTitle('nohead');
		$this->assertSame('nohead', $this->dom->getTitle());
	}

	public function testMagicTitleInvalidHTMLException(){
		$this->expectException(DOMException::class);
		$this->expectExceptionMessage('html header missing');

		$d = new Document;
		$d->setTitle('nope');
	}

	public function testSelector2xpath(){
		$this->assertSame('//html/head/meta[position() = 1]', $this->dom->selector2xpath('html > head > meta:nth-of-type(1)'));
	}

	public function testQuery(){
		$element = $this->dom->query('//html/head/meta[position() = 1]')->offsetGet(0);

		$this->assertSame('UTF-8', $element->getAttribute('charset'));
	}

	public function testQuerySelectorAll(){
		$this->assertSame('en', $this->dom->querySelectorAll('html')->offsetGet(0)->getAttribute('lang'));
	}

#	public function testRemoveElementsBySelector(){
		// first and last line break \n and the rest PHP_EOL?
#		$this->assertEquals('<!DOCTYPE html>'."\n".'<html lang="en">'.PHP_EOL.PHP_EOL.PHP_EOL.'</html>'."\n", $this->dom->removeElementsBySelector(['head', 'body'])->inspect());
#	}

	public function testToNodeList(){
		$nodelist = $this->dom->toNodeList('<meta name="viewport" content="width=device-width, initial-scale=1.0" />');
		$this->assertSame(['name' => 'viewport', 'content' => 'width=device-width, initial-scale=1.0', ], $nodelist->offsetGet(0)->getAttributes());

		$nodelist = $this->dom->toNodeList('<div id="boo" class="bar"></div><div><a href="#foo"></a></div>');
		$this->assertSame(2, $nodelist->count());
		$this->assertSame('boo', $nodelist->offsetGet(0)->getID());
		$this->assertInstanceOf(NodeList::class, $this->dom->toNodeList($nodelist));
	}

	public function testInspect(){
		$this->assertEquals('<meta charset="UTF-8"/>', $this->dom->inspect($this->dom->select(['meta'])[0], true));
	}

	public function testRecursivelyCollect(){
		$this->el = $this->dom->getElementById('content');

		$elements = $this->dom->recursivelyCollect($this->el, 'parentNode');
		$this->assertSame(['body', 'html'], $elements->pluck('nodeName'));

		$elements = $this->dom->recursivelyCollect($this->el, 'parentNode', 1);
		$this->assertSame(['body'], $elements->pluck('nodeName'));
	}

	public function testSelectContinueOnInvalidSelector(){
		$nodes = $this->dom->select([true, 42, [], '#what', '.yummy']);

		$this->assertCount(4, $nodes);
	}

	public function testElementByIdException(){
		$this->expectException(DOMException::class);
		$this->expectExceptionMessage('invalid element id');

		$this->dom->getElementById(42);
	}
}
