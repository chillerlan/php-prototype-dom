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

use DOMElement;
use chillerlan\PrototypeDOM\Element;

class ElementTest extends TestAbstract{

	public function testInstance(){
		$this->assertInstanceOf(Element::class, $this->element);
		$this->assertInstanceOf(DOMElement::class, $this->element);
	}

	public function testID(){
		$this->assertSame('what', $this->element->id('whatever'));
		$this->assertSame('whatever', $this->element->id());
	}

	public function testGetClassnames(){
		$this->assertSame(['foo', 'bar'], $this->element->getClassNames());
	}

	public function testHasClassname(){
		$this->assertTrue($this->element->hasClassName('foo'));
		$this->assertTrue($this->element->hasClassName('bar'));
	}

	public function testAddClassname(){
		$this->assertTrue($this->element->addClassName('what')->hasClassName('what'));
	}

	public function testRemoveClassname(){
		$this->assertFalse($this->element->removeClassName('foo')->hasClassName('foo'));
	}

	public function testToggleClassname(){
		$this->assertFalse($this->element->toggleClassName('foo')->hasClassName('foo'));
		$this->assertTrue($this->element->toggleClassName('foo')->hasClassName('foo'));
	}

	public function testGetStyle(){
		$style = $this->element->getStyle();

		$this->assertSame('#000', $style['background']);
		$this->assertSame('#fff', $style['color']);
	}

	public function testHasStyle(){
		$this->element->setStyle(['display' => 'none']);

		$this->assertSame('none', $this->element->hasStyle('display'));
		$this->assertFalse($this->element->hasStyle('foo'));
	}

	public function testSetStyle(){
		$style = $this->element->setStyle(['display' => 'none'])->getStyle();

		$this->assertSame('#000', $style['background']);
		$this->assertSame('#fff', $style['color']);
		$this->assertSame('none', $style['display']);

		$this->element->setStyle(['display' => 'none'], true);

		$this->assertFalse($this->element->hasStyle('background'));
		$this->assertFalse($this->element->hasStyle('color'));
		$this->assertSame('none', $this->element->hasStyle('display'));
	}

	public function testGetAttributes(){
		$this->assertSame('what', $this->element->getAttributes()['id']);
	}

	public function testSetAttributes(){
		$this->assertSame('bar', $this->element->setAttributes(['foo' => 'bar'])->getAttributes()['foo']);
	}

	public function testRemoveAttributes(){
		$this->element->removeAttributes(['class', 'style']);

		$this->assertFalse($this->element->hasAttribute('class'));
		$this->assertFalse($this->element->hasAttribute('style'));
	}

}
