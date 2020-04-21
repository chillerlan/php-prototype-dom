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

use chillerlan\PrototypeDOM\Node\Element;

class NodeTraversalTest extends TestAbstract{

	public function testRecursivelyCollect(){
		$this->el = $this->dom->getElementById('content');

		$this->assertSame(['body', 'html'], $this->el->ancestors()->pluck('nodeName'));
		$this->assertSame(['div', 'div', 'ul','div', 'div',], $this->el->siblings()->pluck('nodeName'));

		$elements = $this->el->recursivelyCollect('parentNode', 1);
		$this->assertSame(['body'], $elements->pluck('nodeName'));
	}

	public function testInspect(){
		$this->assertSame('<h3 id="title" title="yummy!">Apples</h3>', $this->dom->getElementById('title')->inspect());
		$this->assertSame('<foo></foo>', $this->dom->newElement('foo')->inspect());
	}

	public function testSelect(){
		$this->el = $this->dom->getElementById('apples');

		$callback = function(Element $e){
			return $e->getID();
		};

		$this->assertSame(['title', 'golden-delicious', 'mutsu'], $this->el->select(['[title="yummy!"]'])->map($callback));
		$this->assertSame(['saying', 'golden-delicious', 'mutsu'], $this->el->select(['p#saying', 'li[title="yummy!"]'])->map($callback));
		$this->assertCount(0, $this->el->select(['[title="disgusting!"]']));
	}

	public function testMatch(){
		$this->el = $this->dom->getElementById('fruits');
		$this->assertTrue($this->el->match('ul'));
		$this->assertFalse($this->el->match('p'));

		$this->el = $this->dom->getElementById('mutsu');
		$this->assertTrue($this->el->match('li#mutsu.yummy'));
		$this->assertTrue($this->el->match('[title="yummy!"]'));
	}

	public function testDown(){
		$this->el = $this->dom->getElementById('fruits');

		$this->assertSame('apples', $this->el->down()->getID());
		$this->assertSame('apples', $this->el->down(0)->getID());
		$this->assertSame('golden-delicious', $this->el->down(3)->getID());
		$this->assertNull($this->el->down(42));
		$this->assertSame('golden-delicious', $this->dom->getElementById('apples')->down('li.yummy')->getID());
		$this->assertSame('mutsu', $this->dom->getElementById('apples')->down(['.yummy'], 1)->getID());
	}

	public function testUp(){
		$this->el = $this->dom->getElementById('fruits');
		$this->assertSame('body', $this->el->up()->tagName);
		$this->assertSame('body', $this->el->up(0)->tagName);

		$this->el = $this->dom->getElementById('mutsu');
		$this->assertSame('fruits', $this->el->up(2)->getID());
		$this->assertSame('apples', $this->el->up('li')->getID());
		$this->assertSame('apples', $this->el->up('.keeps-the-doctor-away')->getID());
		$this->assertSame('fruits', $this->el->up('ul', 1)->getID());
		$this->assertNull($this->el->up('div'));
	}

	public function testPrevious(){
		$this->el = $this->dom->getElementById('saying');
		$this->assertSame('list-of-apples', $this->el->previous()->getID());
		$this->assertSame('list-of-apples', $this->el->previous(0)->getID());
		$this->assertSame('h3', $this->el->previous(1)->tagName);
		$this->assertSame('h3', $this->el->previous('h3')->tagName);

		$this->el = $this->dom->getElementById('ida-red');
		$this->assertSame('mutsu', $this->el->previous('.yummy')->getID());
		$this->assertSame('golden-delicious', $this->el->previous('.yummy', 1)->getID());
		$this->assertNull($this->el->previous(5));
	}

	public function testNext(){
		$this->el = $this->dom->getElementById('title');
		$this->assertSame('list-of-apples', $this->el->next()->getID());
		$this->assertSame('list-of-apples', $this->el->next(0)->getID());
		$this->assertSame('saying', $this->el->next(1)->getID());
		$this->assertSame('saying', $this->el->next('p')->getID());

		$this->el = $this->dom->getElementById('golden-delicious');
		$this->assertSame('mutsu', $this->el->next('.yummy')->getID());
		$this->assertSame('ida-red', $this->el->next('.yummy', 1)->getID());

		$this->assertNull($this->dom->getElementById('ida-red')->next());
	}

	public function testChildElements(){
		$this->assertSame('homo-erectus', $this->dom->getElementById('australopithecus')->childElements()->current()->getID());
		$this->assertSame(['homo-neanderthalensis', 'homo-sapiens'], $this->dom->getElementById('homo-erectus')->childElements()->map(function(Element $e){
			return $e->getID();
		}));
		$this->assertCount(0, $this->dom->getElementById('homo-sapiens')->childElements());
	}

	public function testDescendantOf(){
		$this->assertTrue($this->dom->getElementById('homo-sapiens')->descendantOf($this->dom->getElementById('australopithecus')));
		$this->assertFalse($this->dom->getElementById('homo-erectus')->descendantOf($this->dom->getElementById('homo-sapiens')));
	}

	public function testFirstDescendant(){
		$this->assertSame('apples', $this->dom->getElementById('fruits')->firstDescendant()->getID());
		$this->assertSame('homo-erectus', $this->dom->getElementById('australopithecus')->firstDescendant()->getID());

		$this->el = $this->dom->getElementById('homo-erectus');
		$this->assertSame(' Latin is super ', $this->el->firstChild->nodeValue);
		$this->assertSame('homo-neanderthalensis', $this->el->firstDescendant()->getID());
	}

}
