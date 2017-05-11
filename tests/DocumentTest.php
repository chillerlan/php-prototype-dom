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
use chillerlan\PrototypeDOM\{Document, NodeList};

class DocumentTest extends TestAbstract{

	public function testInstance(){
		$this->assertInstanceOf(Document::class, $this->document);
		$this->assertInstanceOf(DOMDocument::class, $this->document);
	}

	public function testMagicTitle(){
		$this->assertSame('Prototype DOM Test', $this->document->title);

		$this->document->title = 'foo';
		$this->assertSame('foo', $this->document->title);

		$this->document->select('head > title')->item(0)->remove();
		$this->assertNull($this->document->title);

		$this->document->title = 'bar';
		$this->assertSame('bar', $this->document->title);
	}

	public function testSelector2xpath(){
		$this->assertSame('//html/head/meta[position() = 1]', $this->document->selector2xpath('html > head > meta:nth-of-type(1)'));
	}

	public function testQuery(){
		$element = $this->document->query('//html/head/meta[position() = 1]')->item(0);

		$this->assertSame('UTF-8', $element->getAttribute('charset'));
	}

	public function testQuerySelectorAll(){
		$this->assertSame('en', $this->document->querySelectorAll('html')->item(0)->getAttribute('lang'));
	}

	public function testRemoveElementsBySelector(){
		// first and last line break \n and the rest PHP_EOL?
		$this->assertEquals('<!DOCTYPE html>'."\n".'<html lang="en"></html>'."\n", $this->document->removeElementsBySelector(['head', 'body'])->inspect());
	}

	public function testToNodeList(){
		$nodelist = $this->document->_toNodeList('<meta name="viewport" content="width=device-width, initial-scale=1.0" />');
		$this->assertSame(['name' => 'viewport', 'content' => 'width=device-width, initial-scale=1.0', ], $nodelist->item(0)->getAttributes());

		$nodelist = $this->document->_toNodeList('<div id="boo" class="bar"></div><div><a href="#foo"></a></div>');
		$this->assertSame(2, $nodelist->length);
		$this->assertSame('boo', $nodelist->item(0)->id);
		$this->assertInstanceOf(NodeList::class, $this->document->_toNodeList($nodelist));
	}

	public function testInspect(){
		$this->assertEquals('<meta charset="UTF-8"/>', $this->document->inspect($this->document->select('meta')[0], true));
	}

	public function testRecursivelyCollect(){
		$this->element = $this->document->getElementById('content');

		$elements = $this->document->recursivelyCollect($this->element, 'parentNode');
		$this->assertSame(['body', 'html'], $elements->pluck('nodeName'));

		$elements = $this->document->recursivelyCollect($this->element, 'parentNode', 1);
		$this->assertSame(['body'], $elements->pluck('nodeName'));
	}

	public function testFoo(){
		/*print_r*/((new Document(file_get_contents(__DIR__.'/../phpunit.xml'), true))->select('filter')->inspect(true));
		/*print_r*/((new Document(file_get_contents(__DIR__.'/test.html')))->select('.yummy')->inspect());

		$this->assertNull(null);
	}

}
