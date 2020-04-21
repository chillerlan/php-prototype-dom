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

		self::assertSame(['body', 'html'], $this->el->ancestors()->pluck('nodeName'));
		self::assertSame(['div', 'div', 'ul','div', 'div',], $this->el->siblings()->pluck('nodeName'));

		$elements = $this->el->recursivelyCollect('parentNode', 1);
		self::assertSame(['body'], $elements->pluck('nodeName'));
	}

	public function testInspect(){
		self::assertSame('<h3 id="title" title="yummy!">Apples</h3>', $this->dom->getElementById('title')->inspect());
		self::assertSame('<foo></foo>', $this->dom->newElement('foo')->inspect());
	}

	public function testSelect(){
		$this->el = $this->dom->getElementById('apples');

		$callback = function(Element $e){
			return $e->getID();
		};

		self::assertSame(['title', 'golden-delicious', 'mutsu'], $this->el->select(['[title="yummy!"]'])->map($callback));
		self::assertSame(['saying', 'golden-delicious', 'mutsu'], $this->el->select(['p#saying', 'li[title="yummy!"]'])->map($callback));
		self::assertCount(0, $this->el->select(['[title="disgusting!"]']));
	}

	public function testMatch(){
		$this->el = $this->dom->getElementById('fruits');
		self::assertTrue($this->el->match('ul'));
		self::assertFalse($this->el->match('p'));

		$this->el = $this->dom->getElementById('mutsu');
		self::assertTrue($this->el->match('li#mutsu.yummy'));
		self::assertTrue($this->el->match('[title="yummy!"]'));
	}

	public function testDown(){
		$this->el = $this->dom->getElementById('fruits');

		self::assertSame('apples', $this->el->down()->getID());
		self::assertSame('apples', $this->el->down(0)->getID());
		self::assertSame('golden-delicious', $this->el->down(3)->getID());
		self::assertNull($this->el->down(42));
		self::assertSame('golden-delicious', $this->dom->getElementById('apples')->down('li.yummy')->getID());
		self::assertSame('mutsu', $this->dom->getElementById('apples')->down(['.yummy'], 1)->getID());
	}

	public function testUp(){
		$this->el = $this->dom->getElementById('fruits');
		self::assertSame('body', $this->el->up()->tagName);
		self::assertSame('body', $this->el->up(0)->tagName);

		$this->el = $this->dom->getElementById('mutsu');
		self::assertSame('fruits', $this->el->up(2)->getID());
		self::assertSame('apples', $this->el->up('li')->getID());
		self::assertSame('apples', $this->el->up('.keeps-the-doctor-away')->getID());
		self::assertSame('fruits', $this->el->up('ul', 1)->getID());
		self::assertNull($this->el->up('div'));
	}

	public function testPrevious(){
		$this->el = $this->dom->getElementById('saying');
		self::assertSame('list-of-apples', $this->el->previous()->getID());
		self::assertSame('list-of-apples', $this->el->previous(0)->getID());
		self::assertSame('h3', $this->el->previous(1)->tagName);
		self::assertSame('h3', $this->el->previous('h3')->tagName);

		$this->el = $this->dom->getElementById('ida-red');
		self::assertSame('mutsu', $this->el->previous('.yummy')->getID());
		self::assertSame('golden-delicious', $this->el->previous('.yummy', 1)->getID());
		self::assertNull($this->el->previous(5));
	}

	public function testNext(){
		$this->el = $this->dom->getElementById('title');
		self::assertSame('list-of-apples', $this->el->next()->getID());
		self::assertSame('list-of-apples', $this->el->next(0)->getID());
		self::assertSame('saying', $this->el->next(1)->getID());
		self::assertSame('saying', $this->el->next('p')->getID());

		$this->el = $this->dom->getElementById('golden-delicious');
		self::assertSame('mutsu', $this->el->next('.yummy')->getID());
		self::assertSame('ida-red', $this->el->next('.yummy', 1)->getID());

		self::assertNull($this->dom->getElementById('ida-red')->next());
	}

	public function testChildElements(){
		self::assertSame('homo-erectus', $this->dom->getElementById('australopithecus')->childElements()->current()->getID());
		self::assertSame(['homo-neanderthalensis', 'homo-sapiens'], $this->dom->getElementById('homo-erectus')->childElements()->map(function(Element $e){
			return $e->getID();
		}));
		self::assertCount(0, $this->dom->getElementById('homo-sapiens')->childElements());
	}

	public function testDescendantOf(){
		self::assertTrue($this->dom->getElementById('homo-sapiens')->descendantOf($this->dom->getElementById('australopithecus')));
		self::assertFalse($this->dom->getElementById('homo-erectus')->descendantOf($this->dom->getElementById('homo-sapiens')));
	}

	public function testFirstDescendant(){
		self::assertSame('apples', $this->dom->getElementById('fruits')->firstDescendant()->getID());
		self::assertSame('homo-erectus', $this->dom->getElementById('australopithecus')->firstDescendant()->getID());

		$this->el = $this->dom->getElementById('homo-erectus');
		self::assertSame(' Latin is super ', $this->el->firstChild->nodeValue);
		self::assertSame('homo-neanderthalensis', $this->el->firstDescendant()->getID());
	}

}
