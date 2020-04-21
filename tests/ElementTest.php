<?php
/**
 * Class ElementTest
 *
 * @filesource   ElementTest.php
 * @created      05.05.2017
 * @package      chillerlan\PrototypeDOMTest
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2017 Smiley
 * @license      MIT
 */

namespace chillerlan\PrototypeDOMTest;

use chillerlan\PrototypeDOM\Node\Element;
use DOMElement;

class ElementTest extends TestAbstract{

	protected function setUp():void{
		parent::setUp();

		$this->el = $this->dom->select(['#what'])[0];
	}

	public function testInstance(){
		self::assertInstanceOf(Element::class, $this->el);
		self::assertInstanceOf(DOMElement::class, $this->el);
	}

	public function testID(){
		self::assertSame('what', $this->el->identify('whatever'));
		self::assertSame('whatever', $this->el->identify());
		self::assertSame('whatever', $this->el->getID());
	}

	public function testGetClassnames(){
		self::assertSame(['foo', 'bar'], $this->el->classNames());
		self::assertSame('foo  bar', $this->el->getClassName());
		// coverage
		self::assertSame([], (new Element('div'))->classNames());
	}

	public function testMagic(){
		$this->el->setID('nope');
		$this->el->setClassName('whatever');
		$this->el->setHref('foo');
		$this->el->setSrc('blah');

		self::assertSame('nope', $this->el->getID());
		self::assertSame('whatever', $this->el->getClassName());
		self::assertSame('foo', $this->el->getHref());
		self::assertSame('blah', $this->el->getSrc());
	}

	public function testHasClassname(){
		self::assertTrue($this->el->hasClassName('foo'));
		self::assertTrue($this->el->hasClassName('bar'));
	}

	public function testAddClassname(){
		self::assertTrue($this->el->addClassName('what')->hasClassName('what'));
	}

	public function testRemoveClassname(){
		self::assertFalse($this->el->removeClassName('foo')->hasClassName('foo'));
	}

	public function testToggleClassname(){
		self::assertFalse($this->el->toggleClassName('foo')->hasClassName('foo'));
		self::assertTrue($this->el->toggleClassName('foo')->hasClassName('foo'));
	}

	public function testGetStyle(){
		$style = $this->el->getStyles();

		self::assertSame('#000', $style['background']);
		self::assertSame('#fff', $style['color']);

		// coverage
		self::assertSame([], (new Element('div'))->getStyles());
	}

	public function testHasStyle(){
		$this->el->setStyle(['display' => 'none']);

		self::assertSame('none', $this->el->getStyle('display'));
		self::assertNull($this->el->getStyle('foo'));
	}

	public function testSetStyle(){
		$style = $this->el->setStyle(['display' => 'none'])->getStyles();

		self::assertSame('#000', $style['background']);
		self::assertSame('#fff', $style['color']);
		self::assertSame('none', $style['display']);

		$this->el->setStyle(['display' => 'none'], true);

		self::assertNull($this->el->getStyle('background'));
		self::assertNull($this->el->getStyle('color'));
		self::assertSame('none', $this->el->getStyle('display'));
	}

	public function testGetAttributes(){
		self::assertSame('what', $this->el->getAttributes()['id']);
	}

	public function testSetAttributes(){
		self::assertSame('bar', $this->el->setAttributes(['foo' => 'bar'])->getAttributes()['foo']);
	}

	public function testRemoveAttributes(){
		$this->el->removeAttributes(['class', 'style']);

		self::assertFalse($this->el->hasAttribute('class'));
		self::assertFalse($this->el->hasAttribute('style'));
	}

}
