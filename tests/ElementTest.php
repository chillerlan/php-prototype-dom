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

	public function testInstance():void{
		$this::assertInstanceOf(Element::class, $this->el);
		$this::assertInstanceOf(DOMElement::class, $this->el);
	}

	public function testID():void{
		$this::assertSame('what', $this->el->identify('whatever'));
		$this::assertSame('whatever', $this->el->identify());
		$this::assertSame('whatever', $this->el->getID());
	}

	public function testGetClassnames():void{
		$this::assertSame(['foo', 'bar'], $this->el->classNames());
		$this::assertSame('foo  bar', $this->el->getClassName());
		// coverage
		$this::assertSame([], (new Element('div'))->classNames());
	}

	public function testMagic():void{
		$this->el->setID('nope');
		$this->el->setClassName('whatever');
		$this->el->setHref('foo');
		$this->el->setSrc('blah');

		$this::assertSame('nope', $this->el->getID());
		$this::assertSame('whatever', $this->el->getClassName());
		$this::assertSame('foo', $this->el->getHref());
		$this::assertSame('blah', $this->el->getSrc());
	}

	public function testHasClassname():void{
		$this::assertTrue($this->el->hasClassName('foo'));
		$this::assertTrue($this->el->hasClassName('bar'));
	}

	public function testAddClassname():void{
		$this::assertTrue($this->el->addClassName('what')->hasClassName('what'));
	}

	public function testRemoveClassname():void{
		$this::assertFalse($this->el->removeClassName('foo')->hasClassName('foo'));
	}

	public function testToggleClassname():void{
		$this::assertFalse($this->el->toggleClassName('foo')->hasClassName('foo'));
		$this::assertTrue($this->el->toggleClassName('foo')->hasClassName('foo'));
	}

	public function testGetStyle():void{
		$style = $this->el->getStyles();

		$this::assertSame('#000', $style['background']);
		$this::assertSame('#fff', $style['color']);

		// coverage
		$this::assertSame([], (new Element('div'))->getStyles());
	}

	public function testHasStyle():void{
		$this->el->setStyle(['display' => 'none']);

		$this::assertSame('none', $this->el->getStyle('display'));
		$this::assertNull($this->el->getStyle('foo'));
	}

	public function testSetStyle():void{
		$style = $this->el->setStyle(['display' => 'none'])->getStyles();

		$this::assertSame('#000', $style['background']);
		$this::assertSame('#fff', $style['color']);
		$this::assertSame('none', $style['display']);

		$this->el->setStyle(['display' => 'none'], true);

		$this::assertNull($this->el->getStyle('background'));
		$this::assertNull($this->el->getStyle('color'));
		$this::assertSame('none', $this->el->getStyle('display'));
	}

	public function testGetAttributes():void{
		$this::assertSame('what', $this->el->getAttributes()['id']);
	}

	public function testSetAttributes():void{
		$this::assertSame('bar', $this->el->setAttributes(['foo' => 'bar'])->getAttributes()['foo']);
	}

	public function testRemoveAttributes():void{
		$this->el->removeAttributes(['class', 'style']);

		$this::assertFalse($this->el->hasAttribute('class'));
		$this::assertFalse($this->el->hasAttribute('style'));
	}

}
