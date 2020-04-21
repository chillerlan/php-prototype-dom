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

use chillerlan\PrototypeDOM\{Document, NodeList};
use chillerlan\PrototypeDOM\Node\{Element, PrototypeNode};
use ArrayAccess, Countable, DOMException, Iterator;
use PHPUnit\Framework\TestCase;

class NodeListTest extends TestCase{

	/**
	 * @var \chillerlan\PrototypeDOM\NodeList
	 */
	protected $nodelist;

	public function testInstance(){
		$this->nodelist = new NodeList;

		self::assertInstanceOf(NodeList::class, $this->nodelist);
		self::assertInstanceOf(Iterator::class, $this->nodelist);
		self::assertInstanceOf(ArrayAccess::class, $this->nodelist);
		self::assertInstanceOf(Countable::class, $this->nodelist);
	}

	public function testToNodelistException(){
		$this->expectException(DOMException::class);
		$this->expectExceptionMessage('invalid content');

		(new Document)->toNodeList(42);
	}

	public function testNodeList(){
		$this->nodelist = (new Document)->toNodeList('<div id="boo" class="bar">content1</div><div><a href="#foo">blah</a></div>');

		// coverage
		$this->nodelist = new NodeList($this->nodelist);

		self::assertCount(2, $this->nodelist);
		self::assertSame(2, $this->nodelist->count());
		self::assertSame('boo', $this->nodelist->first()->getID());

		$this->nodelist->reverse();

		self::assertSame('boo', $this->nodelist->last()->getID());

		$this->nodelist->each(function($node, $i){
			self::assertInstanceOf(PrototypeNode::class, $node);
			unset($this->nodelist[$i]);
			$this->nodelist[$i] = new Element('foo');
			$this->nodelist[] = 'whatever';
		});

		self::assertSame(['foo', 'foo'], $this->nodelist->pluck('tagName'));

		$this->nodelist->each(function($e){
			self::assertSame('foo', $e->tagName);
		});

		self::assertSame('<foo></foo><foo></foo>', trim($this->nodelist->inspect()));
	}

}
