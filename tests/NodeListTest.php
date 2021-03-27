<?php
/**
 * Class NodeListTest
 *
 * @created      09.05.2017
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2017 Smiley
 * @license      MIT
 */

namespace chillerlan\PrototypeDOMTest;

use chillerlan\PrototypeDOM\{Document, NodeList};
use chillerlan\PrototypeDOM\Node\{Element, PrototypeNode};
use ArrayAccess, Countable, DOMException, InvalidArgumentException, Iterator, OutOfBoundsException;

use function array_map;


class NodeListTest extends TestAbstract{

	protected NodeList $nodelist;

	protected function setUp():void{
		parent::setUp();
		$this->nodelist = $this->dom->getElementById('list-of-apples')->childElements();
	}

	public function testInstance():void{
		$this::assertInstanceOf(NodeList::class, $this->nodelist);
		$this::assertInstanceOf(Iterator::class, $this->nodelist);
		$this::assertInstanceOf(ArrayAccess::class, $this->nodelist);
		$this::assertInstanceOf(Countable::class, $this->nodelist);
	}

	public function testToNodelistInvalidContentException():void{
		$this->expectException(DOMException::class);
		$this->expectExceptionMessage('invalid content');

		(new Document)->toNodeList(42);
	}

	public function testNodeListConstruct():void{
		$this->nodelist = new NodeList($this->nodelist);

		$this::assertCount(4, $this->nodelist);
		$this::assertSame(4, $this->nodelist->count());
	}

	public function testSeek():void{
		$this::assertSame(0, $this->nodelist->key());
		$this::assertSame('golden-delicious', $this->nodelist->current()->identify());
		$this->nodelist->seek(1);
		$this::assertSame(1, $this->nodelist->key());
		$this::assertSame('mutsu', $this->nodelist->current()->identify());
	}

	public function testSeekOutOfBoundsException():void{
		$this->expectException(OutOfBoundsException::class);
		$this->expectExceptionMessage('invalid seek position: 42');

		$this->nodelist->seek(42);
	}

	public function testFirstLast():void{
		$this::assertSame('golden-delicious', $this->nodelist->first()->identify());
		$this::assertSame('ida-red', $this->nodelist->last()->identify());
		$this->nodelist->reverse();
		$this::assertSame('ida-red', $this->nodelist->first()->identify());
		$this::assertSame('golden-delicious', $this->nodelist->last()->identify());

	}

	public function testClear():void{
		$this->nodelist->clear();
		$this::assertSame('', trim($this->nodelist->inspect()));
		$this::assertCount(0, $this->nodelist);
	}

	public function testEach():void{

		$this->nodelist->each(function($node, $i){
			$this::assertInstanceOf(PrototypeNode::class, $node);
			unset($this->nodelist[$i]);
			$this->nodelist[$i] = new Element('foo');
			$this->nodelist[] = 'whatever';
		});

		$this::assertSame(['foo', 'foo', 'foo', 'foo'], $this->nodelist->pluck('tagName'));

		$this->nodelist->each(function($e){
			$this::assertSame('foo', $e->tag());
		});

		$this::assertSame('<foo></foo><foo></foo><foo></foo><foo></foo>', trim($this->nodelist->inspect()));
	}

	public function findAllRejectDataProvider():array{
		return [
			'findAll' => ['findAll', ['golden-delicious', 'mutsu', 'ida-red']],
			'reject'  => ['reject', ['mcintosh']],
		];
	}

	/**
	 * @dataProvider findAllRejectDataProvider()
	 */
	public function testFindAllReject(string $fn, array $expected):void{

		$expectedList = array_map(
			fn($element) => $element->identify(),
			$this->nodelist->{$fn}(fn($element) => $element->hasClassName('yummy'))
		);

		$this::assertSame($expected, $expectedList);
	}

	public function iteratingMethodProvider():array{
		return [
			'map'     => ['map'],
			'each'    => ['each'],
			'findAll' => ['findAll'],
			'reject'  => ['reject'],
		];
	}

	/**
	 * @dataProvider iteratingMethodProvider()
	 */
	public function testInvalidIteratorException(string $iteratingMethod):void{
		$this->expectException(InvalidArgumentException::class);
		$this->expectExceptionMessage('invalid iterator');

		$this->nodelist->{$iteratingMethod}('');
	}

}
