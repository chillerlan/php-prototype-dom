<?php
/**
 * Class NodeManipulationTest
 *
 * @created      08.05.2017
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2017 Smiley
 * @license      MIT
 */

namespace chillerlan\PrototypeDOMTest;

use chillerlan\PrototypeDOM\Node\Element;

class NodeManipulationTest extends TestAbstract{

	public function testRemove():void{

		foreach($this->dom->select(['head', 'body']) as $node){
			/** @var \chillerlan\PrototypeDOM\Node\Element $node */
			$node->removeNode();
		}

#		$this::assertSame('<!DOCTYPE html>'."\n".'<html lang="en">'.PHP_EOL.PHP_EOL.PHP_EOL.'</html>'."\n", $this->dom->inspect());

		$this->el = $this->dom->newElement('div', ['id' => 'what'])->update('foo!');
		$this::assertSame('what', $this->el->removeNode()->identify());
	}

	public function testReplace():void{
		$this->el = $this->dom->select(['#what'])[0];
		$this->el->replace($this->dom->newElement('p', ['id' => 'nocontent'])->update('foo'));

		$this::assertSame('foo', $this->dom->getElementById('nocontent')->value());
	}

	public function testWrap():void{
		$this->el = $this->dom->select(['#what'])[0];
		$wrapper  = $this->dom->newElement('section', ['id' => 'nope']);

		$this->el->wrap($wrapper);

		$this::assertSame('what', $this->dom->getElementById('nope')->firstChild->identify());

		$wrapper  = $this->dom->newElement('section', ['id' => 'nope']);
		$this->el = $this->dom->newElement('div', ['id' => 'what'])->update('foo!');
		$this::assertSame('<section id="nope"><div id="what">foo!</div></section>', $this->el->wrap($wrapper)->inspect());
	}

	public function testEmpty():void{
		$this::assertTrue($this->dom->getElementById('wallet')->empty());
		$this::assertFalse($this->dom->getElementById('cart')->empty());

		$this->el = $this->dom->getElementById('list-of-apples');

#		$this::assertSame(9, $this->el->childNodes->length);
		$this::assertSame(4, $this->el->childElements()->count());
		$this::assertFalse($this->el->empty());

		$this->el->purge();

		$this::assertSame(0, $this->el->childElements()->count());
		$this::assertSame(0, $this->el->childNodes->length);
		$this::assertTrue($this->el->empty());
	}

	public function testUpdate():void{
		$this->el = $this->dom->select(['#what'])[0];

		$this->el->update('<div id="boo" class="bar">content1</div>');

		$this::assertSame('content1', $this->dom->getElementById('boo')->value());
		$this::assertTrue($this->dom->getElementById('boo')->hasClassName('bar'));
	}

	public function testCleanWhitespace():void{
		$this->el = $this->dom->getElementById('wallet');
		$this::assertSame('<div id="wallet">     </div>', $this->el->inspect());

		$this->el->cleanWhitespace();
		$this::assertSame('<div id="wallet"></div>', $this->el->inspect());
	}

	public function testInsert():void{
		$this->el = $this->dom->getElementById('content');

		$this->el
			->insert($this->dom->newElement('p', ['id' => 'whatever'])->update('bottom1'))
			->insert([
				'top' => '<div id="top1">top1</div><div id="top2">top2</div>',
				'bottom' => '<div id="bottom2">bottom2</div><div id="bottom3">bottom3</div>',
				'before' => '<div id="before1"></div><div id="before2"></div>',
				'after' => '<div id="after1"></div><div id="after2"></div>',
			])
			->next(2)
			->insert('<div id="after3"><a></a></div>')
			->up()
			->insert(['after' => '<div id="after8"></div>']);

		$this::assertNull($this->el->up(2));

		$this::assertSame('bottom3', $this->dom->getElementById('bottom3')->value());

		$this->dom->getElementById('after8')->insert_top(new Element('div'));
	}

}
