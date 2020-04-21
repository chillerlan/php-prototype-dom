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

use OutOfBoundsException;
use chillerlan\PrototypeDOM\Node\PrototypeNode;
use DOMNode, DOMNodeList;

use function array_column, array_key_exists, array_merge, array_reverse, count, is_int, is_iterable, iterator_to_array;


class NodeList implements EnumerableInterface{

	/**
	 * @var array
	 */
	protected array $array = [];

	/**
	 * @var int
	 */
	protected int $offset = 0;

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

	/*************
	 * Countable *
	 *************/

	/**
	 * @link http://php.net/manual/countable.count.php
	 * @inheritdoc
	 */
	public function count():int{
		return count($this->array);
	}

	/************
	 * Iterator *
	 ************/

	/**
	 * @link  http://php.net/manual/iterator.current.php
	 * @inheritdoc
	 */
	public function current():?DOMNode{
		return $this->array[$this->offset] ?? null;
	}

	/**
	 * @link  http://php.net/manual/iterator.next.php
	 * @inheritdoc
	 */
	public function next():void{
		$this->offset++;
	}

	/**
	 * @link  http://php.net/manual/iterator.key.php
	 * @inheritdoc
	 */
	public function key():int{
		return $this->offset;
	}

	/**
	 * @link  http://php.net/manual/iterator.valid.php
	 * @inheritdoc
	 */
	public function valid():bool{
		return array_key_exists($this->offset, $this->array);
	}

	/**
	 * @link  http://php.net/manual/iterator.rewind.php
	 * @inheritdoc
	 */
	public function rewind():void{
		$this->offset = 0;
	}

	/********************
	 * SeekableIterator *
	 ********************/

	/**
	 * @link  http://php.net/manual/seekableiterator.seek.php
	 * @inheritdoc
	 */
	public function seek($pos):void{
		$this->rewind();

		for( ; $this->offset < $pos; ){

			if(!\next($this->array)) {
				throw new OutOfBoundsException('invalid seek position: '.$pos);
			}

			$this->offset++;
		}

	}

	/***************
	 * ArrayAccess *
	 ***************/

	/**
	 * @link  http://php.net/manual/arrayaccess.offsetexists.php
	 * @inheritdoc
	 */
	public function offsetExists($offset):bool{
		return array_key_exists($offset, $this->array);
	}

	/**
	 * @link  http://php.net/manual/arrayaccess.offsetget.php
	 * @inheritdoc
	 * @return \chillerlan\PrototypeDOM\Node\PrototypeNode|\DOMNode|null
	 */
	public function offsetGet($offset):?DOMNode{
		return $this->array[$offset] ?? null;
	}

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
	 * @link  http://php.net/manual/arrayaccess.offsetunset.php
	 * @inheritdoc
	 */
	public function offsetUnset($offset):void{
		unset($this->array[$offset]);
	}

	/*************
	 * Prototype *
	 *************/

	/**
	 * @link http://api.prototypejs.org/language/Array/prototype/first/
	 *
	 * @return \chillerlan\PrototypeDOM\Node\PrototypeNode|\DOMNode|null
	 */
	public function first():?DOMNode{
		return $this->array[0] ?? null;
	}

	/**
	 * @link http://api.prototypejs.org/language/Array/prototype/last/
	 *
	 * @return \chillerlan\PrototypeDOM\Node\PrototypeNode|\DOMNode|null
	 */
	public function last():?DOMNode{
		return $this->array[\count($this->array) - 1] ?? null;
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

	/**
	 * @link http://api.prototypejs.org/language/Enumerable/prototype/each/
	 *
	 * @param callable $callback
	 *
	 * @return $this
	 */
	public function each($callback){
		$this->map($callback);

		return $this;
	}

	/**
	 * @link http://api.prototypejs.org/language/Enumerable/prototype/collect/
	 * @link http://api.prototypejs.org/language/Enumerable/prototype/map/
	 *
	 * @param callable $callback
	 *
	 * @return array
	 * @throws \Exception
	 */
	public function map($callback):array {

		if(!\is_callable($callback)){
			throw new \Exception('invalid callback');
		}

		$return = [];

		foreach($this->array as $index => $element){
			$return[$index] = \call_user_func_array($callback, [$element, $index]);
		}

		return $return;
	}

	/**
	 * @return \chillerlan\PrototypeDOM\NodeList
	 */
	public function clear():NodeList{
		$this->array  = [];
		$this->offset = 0;

		return $this;
	}

	/**
	 * @link http://api.prototypejs.org/language/Enumerable/prototype/findAll/
	 *
	 * @param callable $callback
	 *
	 * @return array
	 * @throws \Exception
	 */
	public function findAll($callback):array{

		if(!\is_callable($callback)){
			throw new \Exception('invalid callback');
		}

		$return = [];

		foreach($this->array as $index => $element){

			if(\call_user_func_array($callback, [$element, $index]) === true){
				$return[] = $element;
			}

		}

		return $return;
	}

	/**
	 * @link http://api.prototypejs.org/language/Enumerable/prototype/reject/
	 *
	 * @param callable $callback
	 *
	 * @return array
	 * @throws \Exception
	 */
	public function reject($callback):array{

		if(!\is_callable($callback)){
			throw new \Exception('invalid callback');
		}

		$return = [];

		foreach($this->array as $index => $element){

			if(\call_user_func_array($callback, [$element, $index]) !== true){
				$return[] = $element;
			}

		}

		return $return;
	}

}
