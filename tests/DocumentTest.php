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

use DOMDocument, DOMNodeList;
use chillerlan\PrototypeDOM\Document;

class DocumentTest extends TestAbstract{

	public function testInstance(){
		$this->assertInstanceOf(Document::class, $this->document);
		$this->assertInstanceOf(DOMDocument::class, $this->document);
	}

	public function testSelector2xpath(){
		$this->assertSame('//html/head/meta[position() = 1]', $this->document->selector2xpath('html > head > meta:nth-of-type(1)'));
	}

	public function testQuery(){
		$this->element = $this->document->query('//html/head/meta[position() = 1]')->item(0);

		$this->assertSame('UTF-8', $this->element->getAttribute('charset'));
	}

	public function testGetElementsBySelector(){
		$this->assertSame('en', $this->document->getElementsBySelector('html')->item(0)->getAttribute('lang'));
	}

	public function testInspect(){
		$this->assertEquals('<meta charset="UTF-8"/>', $this->document->inspect($this->document->select('meta')[0], true));
	}

	public function testRemoveElementsBySelector(){
		// first and last line break \n and the rest PHP_EOL?
		$this->assertEquals('<!DOCTYPE html>'."\n".'<html lang="en"></html>'."\n", $this->document->removeElementsBySelector(['head', 'body'])->inspect());
	}

	public function testToDOMNodeList(){
		$nodelist = $this->document->_toDOMNodeList('<div id="boo" class="bar">content1</div><div><a href="#foo">blah</a></div>');

		$this->assertSame(2, $nodelist->length);
		$this->assertSame('blah', $nodelist->item(1)->nodeValue);
		$this->assertInstanceOf(DOMNodeList::class, $this->document->_toDOMNodeList($nodelist));
	}

	public function testRecursivelyCollect(){
		$this->element = $this->document->getElementById('content');

		$elements = $this->document->recursivelyCollect($this->element, 'parentNode');
		$this->assertSame(['body', 'html'], array_column($elements, 'nodeName'));

		$elements = $this->document->recursivelyCollect($this->element, 'parentNode', 1);
		$this->assertSame(['body'], array_column($elements, 'nodeName'));
	}

}
