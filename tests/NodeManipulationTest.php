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

class NodeManipulationTest extends TestAbstract{

	public function testRemove(){

		foreach($this->document->select(['head', 'body']) as $node){
			/** @var \chillerlan\PrototypeDOM\ManipulationTrait $node */
			$node->remove();
		}

		$this->assertSame('<!DOCTYPE html>'."\n".'<html lang="en"></html>'."\n", $this->document->inspect());

		$element = $this->document->newElement('div', ['id' => 'what'])->update('foo!');
		$this->assertSame('what', $element->remove()->id());
	}

	public function testReplace(){
		$this->element->replace($this->document->newElement('p', ['id' => 'nocontent'])->update('foo'));

		$this->assertSame('foo', $this->document->getElementById('nocontent')->nodeValue);
	}

	public function testWrap(){
		$wrapper = $this->document->newElement('section', ['id' => 'nope']);
		$this->element->wrap($wrapper);

		$this->assertSame('what', $this->document->getElementById('nope')->firstChild->id());

		$wrapper = $this->document->newElement('section', ['id' => 'nope']);
		$element = $this->document->newElement('div', ['id' => 'what'])->update('foo!');
		$this->assertSame('<section id="nope"><div id="what">foo!</div></section>', $element->wrap($wrapper)->inspect());
	}

	public function testEmpty(){
		$this->assertSame(0, $this->element->empty()->childNodes->length);
	}

	public function testUpdate(){
		$this->element->update('<div id="boo" class="bar">content1</div>');

		$this->assertSame('content1', $this->document->getElementById('boo')->nodeValue);
		$this->assertTrue($this->document->getElementById('boo')->hasClassName('bar'));
	}

	public function testCleanWhitespace(){
		$this->element = $this->document->getElementById('content')->next();
		$this->assertSame('<div>  </div>', $this->element->inspect());

		$this->element->cleanWhitespace();
		$this->assertSame('<div></div>', $this->element->inspect());
	}

	public function testInsert(){
		$this->element = $this->document->getElementById('content');

		$this->element
			->insert($this->document->newElement('p', ['id' => 'whatever'])->update('bottom1'))
			->insert([
				'top' => '<div id="top1">top1</div><div id="top2">top2</div>',
				'bottom' => '<div id="bottom2">bottom2</div><div id="bottom3">bottom3</div>',
				'before' => '<div id="before1"></div><div id="before2"></div>',
				'after' => '<div id="after1"></div><div id="after2"></div>',
			])
			->next(2)
			->insert('<div id="after3"><a></a></div>')
			->up()
			->insert(['after' => '<div id="after8"><a></a></div>']);

		$this->assertNull($this->element->up(2));

		$this->assertSame('bottom3', $this->document->getElementById('bottom3')->nodeValue);
		print_r($this->document->inspect());
#		$this->markTestSkipped('@todo testInsert');
	}

}
