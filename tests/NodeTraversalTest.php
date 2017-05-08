<?php
/**
 * Class NodeTraversalTest
 *
 * @filesource   NodeTraversalTest.php
 * @created      08.05.2017
 * @package      chillerlan\PrototypeDOMTest
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2017 Smiley
 * @license      MIT
 */

namespace chillerlan\PrototypeDOMTest;

class NodeTraversalTest extends TestAbstract{

	public function testRecursivelyCollect(){
		$this->element = $this->document->getElementById('content');

		$this->assertSame(['body', 'html'], array_column($this->element->ancestors(), 'nodeName'));
		$this->assertSame(['div', 'div'], array_column($this->element->siblings(), 'nodeName'));

		$elements = $this->element->recursivelyCollect('parentNode', 1);
		$this->assertSame(['body'], array_column($elements, 'nodeName'));
	}

	public function testInspect(){
		$this->assertEquals('<div id="what" class=" foo  bar " style="background: #000; color: #fff">content</div>', $this->element->inspect());
	}

	public function testSelect(){
		$this->element = $this->document->getElementById('content');

		$e = ['some stuff', 'more stuff', 'other stuff'];
		$this->assertSame($e, array_column($this->element->select('*'), 'nodeValue'));
		$this->assertSame($e, array_column($this->element->descendants(), 'nodeValue'));
	}

	public function testMatch(){
		$this->assertFalse($this->element->match('ul.bar'));

		$this->assertTrue($this->element->match('div.foo.bar'));
		$this->assertTrue($this->element->match('#what'));
		$this->assertTrue($this->element->match('body > div'));
	}

	public function testDown(){
		$this->element = $this->document->getElementById('stuff');

		$this->assertSame('content', $this->element->down('div', 1)->id());
		$this->assertSame('what', $this->element->down()->id());
		$this->assertSame('other', $this->element->down(1)->down(2)->id());
	}

	public function testUp(){
		$this->element = $this->document->getElementById('other');

		$this->assertSame('content', $this->element->up()->id());
		$this->assertSame('what', $this->element->up('body')->down()->id());
	}

	public function testPrevious(){
		$this->element = $this->document->getElementById('other');

		$this->assertSame('some', $this->element->previous(1)->id());
		$this->assertSame('more', $this->element->previous()->id());
		$this->assertSame('more', $this->element->previous(0)->id());
	}

	public function testNext(){
		$this->element = $this->document->getElementById('some');

		$this->assertSame('other', $this->element->next(1)->id());
		$this->assertSame('more', $this->element->next()->id());
		$this->assertSame('more', $this->element->next(0)->id());
	}

	public function testChildElements(){
		$this->element = $this->document->getElementById('content');

		$this->assertSame(['some stuff', 'more stuff', 'other stuff'], array_column($this->element->childElements(), 'nodeValue'));
	}

	public function testDescendantOf(){
		$this->element = $this->document->getElementById('some');

		$this->assertFalse($this->element->descendantOf($this->document->getElementById('what')));

		$this->assertTrue($this->element->descendantOf($this->document->getElementById('stuff')));
		$this->assertTrue($this->element->descendantOf($this->document->getElementById('content')));
	}

	public function testFirstDescendant(){
		$this->element = $this->document->getElementById('content');

		$this->assertSame('some stuff', $this->element->firstDescendant()->nodeValue);
	}

}
