<?php
/**
 * Class NodeManipulationTest
 *
 * @filesource   NodeManipulationTest.php
 * @created      08.05.2017
 * @package      chillerlan\PrototypeDOMTest
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2017 Smiley
 * @license      MIT
 */

namespace chillerlan\PrototypeDOMTest;

use chillerlan\PrototypeDOM\Node\Element;

class NodeManipulationTest extends TestAbstract{

	public function testRemove(){

		foreach($this->dom->select(['head', 'body']) as $node){
			/** @var \chillerlan\PrototypeDOM\Node\Element $node */
			$node->remove();
		}

#		self::assertSame('<!DOCTYPE html>'."\n".'<html lang="en">'.PHP_EOL.PHP_EOL.PHP_EOL.'</html>'."\n", $this->dom->inspect());

		$this->el = $this->dom->newElement('div', ['id' => 'what'])->update('foo!');
		self::assertSame('what', $this->el->remove()->getID());
	}

	public function testReplace(){
		$this->el = $this->dom->select(['#what'])[0];
		$this->el->replace($this->dom->newElement('p', ['id' => 'nocontent'])->update('foo'));

		self::assertSame('foo', $this->dom->getElementById('nocontent')->nodeValue);
	}

	public function testWrap(){
		$this->el = $this->dom->select(['#what'])[0];
		$wrapper  = $this->dom->newElement('section', ['id' => 'nope']);

		$this->el->wrap($wrapper);

		self::assertSame('what', $this->dom->getElementById('nope')->firstChild->getID());

		$wrapper  = $this->dom->newElement('section', ['id' => 'nope']);
		$this->el = $this->dom->newElement('div', ['id' => 'what'])->update('foo!');
		self::assertSame('<section id="nope"><div id="what">foo!</div></section>', $this->el->wrap($wrapper)->inspect());
	}

	public function testEmpty(){
		self::assertTrue($this->dom->getElementById('wallet')->empty());
		self::assertFalse($this->dom->getElementById('cart')->empty());

		$this->el = $this->dom->getElementById('list-of-apples');

#		self::assertSame(9, $this->el->childNodes->length);
		self::assertSame(4, $this->el->childElements()->count());
		self::assertFalse($this->el->empty());

		$this->el->purge();

		self::assertSame(0, $this->el->childElements()->count());
		self::assertSame(0, $this->el->childNodes->length);
		self::assertTrue($this->el->empty());
	}

	public function testUpdate(){
		$this->el = $this->dom->select(['#what'])[0];

		$this->el->update('<div id="boo" class="bar">content1</div>');

		self::assertSame('content1', $this->dom->getElementById('boo')->nodeValue);
		self::assertTrue($this->dom->getElementById('boo')->hasClassName('bar'));
	}

	public function testCleanWhitespace(){
		$this->el = $this->dom->getElementById('wallet');
		self::assertSame('<div id="wallet">     </div>', $this->el->inspect());

		$this->el->cleanWhitespace();
		self::assertSame('<div id="wallet"></div>', $this->el->inspect());
	}

	public function testInsert(){
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

		self::assertNull($this->el->up(2));

		self::assertSame('bottom3', $this->dom->getElementById('bottom3')->nodeValue);

		$this->dom->getElementById('after8')->insert_top(new Element('div'));
	}

}
