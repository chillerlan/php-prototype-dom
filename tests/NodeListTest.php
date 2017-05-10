<?php
/**
 * Class NodeListTest
 *
 * @filesource   NodeListTest.php
 * @created      09.05.2017
 * @package      chillerlan\PrototypeDOMTest
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2017 Smiley
 * @license      MIT
 */

namespace chillerlan\PrototypeDOMTest;

use chillerlan\PrototypeDOM\Document;
use chillerlan\PrototypeDOM\Element;
use Iterator, ArrayAccess, Countable;
use chillerlan\PrototypeDOM\NodeList;
use PHPUnit\Framework\TestCase;

class NodeListTest extends TestCase{

	/**
	 * @var \chillerlan\PrototypeDOM\NodeList
	 */
	protected $nodelist;

	protected function setUp(){}

	public function testInstance(){
		$this->nodelist = new NodeList;

		$this->assertInstanceOf(NodeList::class, $this->nodelist);
		$this->assertInstanceOf(Iterator::class, $this->nodelist);
		$this->assertInstanceOf(ArrayAccess::class, $this->nodelist);
		$this->assertInstanceOf(Countable::class, $this->nodelist);
	}

	public function testLoad(){
		$this->nodelist = new NodeList((new Document)->_loadHTMLFragment('<div id="boo" class="bar">content1</div><div><a href="#foo">blah</a></div>'));

		$this->assertCount(2, $this->nodelist);

		$this->assertSame('boo', $this->nodelist->first()->id());

		$this->nodelist->reverse();

		$this->assertSame('boo', $this->nodelist->last()->id());


		foreach($this->nodelist as $i => $node){
			$this->assertInstanceOf(Element::class, $node);
			unset($this->nodelist[$i]);
		}

		$this->assertCount(0, $this->nodelist);

	}

}
