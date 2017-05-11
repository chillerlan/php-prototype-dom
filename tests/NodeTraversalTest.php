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
		$element = $this->_e('content');

		$this->assertSame(['body', 'html'], $element->ancestors()->pluck('nodeName'));
		$this->assertSame(['div', 'div', 'ul','div', 'div',], $element->siblings()->pluck('nodeName'));

		$elements = $element->recursivelyCollect('parentNode', 1);
		$this->assertSame(['body'], $elements->pluck('nodeName'));
	}

	public function testInspect(){
		$this->assertEquals('<h3 id="title" title="yummy!">Apples</h3>', $this->_e('title')->inspect());
		$this->assertEquals('<foo></foo>', $this->document->newElement('foo')->inspect());
		$this->assertEquals('<foo></foo>', $this->document->newElement('foo')->innerHTML);
	}

	public function testSelect(){
		$element = $this->_e('apples');

		$this->assertSame(['title', 'golden-delicious', 'mutsu'], $element->select('[title="yummy!"]')->map([$this, 'getID']));
		$this->assertSame(['saying', 'golden-delicious', 'mutsu'], $element->select(['p#saying', 'li[title="yummy!"]'])->map([$this, 'getID']));
		$this->assertCount(0, $element->select('[title="disgusting!"]'));
	}

	public function testMatch(){
		$element = $this->_e('fruits');
		$this->assertTrue($element->match('ul'));
		$this->assertFalse($element->match('p'));

		$element = $this->_e('mutsu');
		$this->assertTrue($element->match('li#mutsu.yummy'));
		$this->assertTrue($element->match('[title="yummy!"]'));
	}

	public function testDown(){
		$element = $this->_e('fruits');

		$this->assertSame('apples', $element->down()->id);
		$this->assertSame('apples', $element->down(0)->id);
		$this->assertSame('golden-delicious', $element->down(3)->id);
		$this->assertNull($element->down(42));
		$this->assertSame('golden-delicious', $this->_e('apples')->down('li.yummy')->id);
		$this->assertSame('mutsu', $this->_e('apples')->down('.yummy', 1)->id);
	}

	public function testUp(){
		$element = $this->_e('fruits');
		$this->assertSame('body', $element->up()->tagName);
		$this->assertSame('body', $element->up(0)->tagName);

		$element = $this->_e('mutsu');
		$this->assertSame('fruits', $element->up(2)->id);
		$this->assertSame('apples', $element->up('li')->id);
		$this->assertSame('apples', $element->up('.keeps-the-doctor-away')->id);
		$this->assertSame('fruits', $element->up('ul', 1)->id);
		$this->assertNull($element->up('div'));
	}

	public function testPrevious(){
		$element = $this->_e('saying');
		$this->assertSame('list-of-apples', $element->previous()->id);
		$this->assertSame('list-of-apples', $element->previous(0)->id);
		$this->assertSame('h3', $element->previous(1)->tagName);
		$this->assertSame('h3', $element->previous('h3')->tagName);

		$element = $this->_e('ida-red');
		$this->assertSame('mutsu', $element->previous('.yummy')->id);
		$this->assertSame('golden-delicious', $element->previous('.yummy', 1)->id);
		$this->assertNull($element->previous(5));
	}

	public function testNext(){
		$element = $this->_e('title');
		$this->assertSame('list-of-apples', $element->next()->id);
		$this->assertSame('list-of-apples', $element->next(0)->id);
		$this->assertSame('saying', $element->next(1)->id);
		$this->assertSame('saying', $element->next('p')->id);

		$element = $this->_e('golden-delicious');
		$this->assertSame('mutsu', $element->next('.yummy')->id);
		$this->assertSame('ida-red', $element->next('.yummy', 1)->id);

		$this->assertNull($this->_e('ida-red')->next());
	}

	public function testChildElements(){
		$this->assertSame('homo-erectus', $this->_e('australopithecus')->childElements()->current()->id);
		$this->assertSame(['homo-neanderthalensis', 'homo-sapiens'], $this->_e('homo-erectus')->childElements()->map([$this, 'getID']));
		$this->assertCount(0, $this->_e('homo-sapiens')->childElements());
	}

	public function testDescendantOf(){
		$this->assertTrue($this->_e('homo-sapiens')->descendantOf($this->_e('australopithecus')));
		$this->assertFalse($this->_e('homo-erectus')->descendantOf($this->_e('homo-sapiens')));
	}

	public function testFirstDescendant(){
		$this->assertSame('apples', $this->_e('fruits')->firstDescendant()->id);
		$this->assertSame('homo-erectus', $this->_e('australopithecus')->firstDescendant()->id);

		$element = $this->_e('homo-erectus');
		$this->assertSame(' Latin is super ', $element->firstChild->nodeValue);
		$this->assertSame('homo-neanderthalensis', $element->firstDescendant()->id);
	}

}
