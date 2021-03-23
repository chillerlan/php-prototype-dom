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

	protected NodeList $nodelist;

	public function testInstance():void{
		$this->nodelist = new NodeList;

		$this::assertInstanceOf(NodeList::class, $this->nodelist);
		$this::assertInstanceOf(Iterator::class, $this->nodelist);
		$this::assertInstanceOf(ArrayAccess::class, $this->nodelist);
		$this::assertInstanceOf(Countable::class, $this->nodelist);
	}

	public function testToNodelistException():void{
		$this->expectException(DOMException::class);
		$this->expectExceptionMessage('invalid content');

		(new Document)->toNodeList(42);
	}

	public function testNodeList():void{
		$this->nodelist = (new Document)->toNodeList('<div id="boo" class="bar">content1</div><div><a href="#foo">blah</a></div>');

		// coverage
		$this->nodelist = new NodeList($this->nodelist);

		$this::assertCount(2, $this->nodelist);
		$this::assertSame(2, $this->nodelist->count());
		$this::assertSame('boo', $this->nodelist->first()->getID());

		$this->nodelist->reverse();

		$this::assertSame('boo', $this->nodelist->last()->getID());

		$this->nodelist->each(function($node, $i){
			$this::assertInstanceOf(PrototypeNode::class, $node);
			unset($this->nodelist[$i]);
			$this->nodelist[$i] = new Element('foo');
			$this->nodelist[] = 'whatever';
		});

		$this::assertSame(['foo', 'foo'], $this->nodelist->pluck('tagName'));

		$this->nodelist->each(function($e){
			$this::assertSame('foo', $e->tagName);
		});

		$this::assertSame('<foo></foo><foo></foo>', trim($this->nodelist->inspect()));
	}

}
