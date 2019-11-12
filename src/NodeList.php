<?php
/**
 * Class NodeList
 *
 * @filesource   NodeList.php
 * @created      09.05.2017
 * @package      chillerlan\PrototypeDOM
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2017 Smiley
 * @license      MIT
 */

namespace chillerlan\PrototypeDOM;

use ArrayAccess;
use chillerlan\PrototypeDOM\Node\{PrototypeNode};
use chillerlan\Traits\{Enumerable, Interfaces\ArrayAccessTrait, Magic};
use chillerlan\Traits\SPL\{CountableTrait, SeekableIteratorTrait};
use Countable, DOMNode, DOMNodeList, SeekableIterator;

use function array_column, array_merge, array_reverse, is_int, is_iterable, iterator_to_array;

/**
 * @property int $length
 *
 * @method \chillerlan\PrototypeDOM\Node\PrototypeNode current()
 * @method int key()
 * @method bool valid()
 * @method void next()
 * @method void rewind()
 * @method void seek($pos)
 * @method bool offsetExists($offset)
 * @method \chillerlan\PrototypeDOM\Node\PrototypeNode|null offsetGet($offset)
 * @method void offsetUnset($offset)
 */
class NodeList implements SeekableIterator, ArrayAccess, Countable{
	use Magic, SeekableIteratorTrait, ArrayAccessTrait, CountableTrait;
	use Enumerable{
		__each as each;
		__findAll as findAll;
		__map as map;
		__reject as reject;
		__toArray as toArray;
	}

	/**
	 * NodeList constructor.
	 *
	 * @param \DOMNodeList $nodes
	 *
	 * @throws \Exception
	 */
	public function __construct(iterable $nodes = null){

		if($nodes instanceof DOMNodeList){
			$this->array = iterator_to_array($nodes);
		}
		elseif($nodes instanceof NodeList){
			$this->array = $nodes->toArray();
		}
		elseif(is_iterable($nodes)){
			foreach($nodes as $node){
				if($node instanceof DOMNode || $node instanceof PrototypeNode){
					$this->array[] = $node;
				}
			}
		}

	}

	/*********
	 * magic *
	 *********/

	/**
	 * @return int
	 */
	public function magic_get_length():int{
		return $this->count();
	}


	/***********
	 * generic *
	 ***********/

	/**
	 * @param \DOMNode $node
	 *
	 * @return bool
	 */
	public function match(DOMNode $node):bool{
		/** @var \chillerlan\PrototypeDOM\Node\Element $element */
		foreach($this->array as $element){

			if($element->isSameNode($node)){
				return true;
			}

		}

		return false;
	}

	/**
	 * @param int $offset
	 *
	 * @return \chillerlan\PrototypeDOM\Node\PrototypeNode|\DOMNode|null
	 */
	public function item(int $offset):?DOMNode{
		return $this->array[$offset] ?? null;
	}

	/**
	 * @param \chillerlan\PrototypeDOM\NodeList $nodelist
	 *
	 * @return \chillerlan\PrototypeDOM\NodeList
	 */
	public function merge(NodeList $nodelist):NodeList{
		$this->array = array_merge($this->array, $nodelist->toArray());

		return $this;
	}

	/**
	 * @param bool $xml
	 *
	 * @return string
	 */
	public function inspect(bool $xml = false):string{
		return (new Document($this, $xml))->inspect(null, $xml);
	}

	/***************
	 * ArrayAccess *
	 ***************/

	/**
	 * @param int      $offset
	 * @param \DOMNode $value
	 *
	 * @return void
	 */
	public function offsetSet($offset, $value):void{

		if($value instanceof DOMNode){

			is_int($offset)
				? $this->array[$offset] = $value
				: $this->array[] = $value;

		}

	}

	/**
	 * @link http://api.prototypejs.org/language/Array/prototype/first/
	 *
	 * @return \chillerlan\PrototypeDOM\Node\PrototypeNode|\DOMNode|null
	 */
	public function first():?DOMNode{
		return $this->__first();
	}

	/**
	 * @link http://api.prototypejs.org/language/Array/prototype/last/
	 *
	 * @return \chillerlan\PrototypeDOM\Node\PrototypeNode|\DOMNode|null
	 */
	public function last():?DOMNode{
		return $this->__last();
	}

	/**
	 * @link http://api.prototypejs.org/language/Enumerable/prototype/pluck/
	 *
	 * @param string $property
	 *
	 * @return array
	 */
	public function pluck(string $property):array{
		return array_column($this->array, $property);
	}

	/**
	 * @link http://api.prototypejs.org/language/Array/prototype/reverse/
	 *
	 * @return \chillerlan\PrototypeDOM\NodeList
	 */
	public function reverse():NodeList{
		$this->array  = array_reverse($this->array);
		$this->offset = 0;

		return $this;
	}

	/**
	 * @link http://api.prototypejs.org/language/Enumerable/prototype/toArray/
	 *
	 * @return array
	 */
	public function toArray():array{
		return $this->array;
	}

}
