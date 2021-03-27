<?php
/**
 * Class NodeList
 *
 * @created      09.05.2017
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2017 Smiley
 * @license      MIT
 */

namespace chillerlan\PrototypeDOM;

use chillerlan\PrototypeDOM\Node\PrototypeNode;
use ArrayAccess, Countable, DOMNode, DOMNodeList, InvalidArgumentException, OutOfBoundsException, SeekableIterator;

use function array_column, array_key_exists, array_merge, array_reverse, call_user_func_array, count,
	is_callable, is_int, is_iterable, iterator_to_array, next;

class NodeList implements SeekableIterator, ArrayAccess, Countable{

	/**
	 * @var array<\chillerlan\PrototypeDOM\Node\PrototypeNode|\chillerlan\PrototypeDOM\Node\PrototypeHTMLElement|\DOMNode>
	 */
	protected array $array = [];

	protected int $offset = 0;

	/**
	 * NodeList constructor.
	 */
	public function __construct(iterable $nodes = null){

		if($nodes !== null){
			$this->fromIterable($nodes);
		}

	}


	/***********
	 * generic *
	 ***********/

	public function fromIterable(iterable $nodes):NodeList{

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

		$this->rewind();

		return $this;
	}

	/**
	 * Checks if an element in the NodeList matches the given DOMNode
	 */
	public function match(DOMNode $node):bool{

		foreach($this->array as $element){

			if($element->isSameNode($node)){
				return true;
			}

		}

		return false;
	}

	/**
	 * Merges one NodeList into another
	 */
	public function merge(NodeList $nodelist):NodeList{
		$this->array = array_merge($this->array, $nodelist->toArray());

		return $this;
	}

	/**
	 * Returns the item on $offset or null if none is found
	 *
	 * @return \DOMNode|\chillerlan\PrototypeDOM\Node\PrototypeHTMLElement|null
	 */
	public function item(int $offset):?DOMNode{
		return $this->offsetGet($offset);
	}

	/*************
	 * Countable *
	 *************/

	/**
	 * @inheritDoc
	 */
	public function count():int{
		return count($this->array);
	}

	/************
	 * Iterator *
	 ************/

	/**
	 * @inheritDoc
	 *
	 * @return \DOMNode|\chillerlan\PrototypeDOM\Node\PrototypeHTMLElement|null
	 */
	public function current():?DOMNode{
		return $this->array[$this->offset] ?? null;
	}

	/**
	 * @inheritDoc
	 */
	public function next():void{
		$this->offset++;
	}

	/**
	 * @inheritDoc
	 */
	public function key():int{
		return $this->offset;
	}

	/**
	 * @inheritDoc
	 */
	public function valid():bool{
		return array_key_exists($this->offset, $this->array);
	}

	/**
	 * @inheritDoc
	 */
	public function rewind():void{
		$this->offset = 0;
	}

	/********************
	 * SeekableIterator *
	 ********************/

	/**
	 * @inheritDoc
	 */
	public function seek($offset):void{
		$this->rewind();

		for( ; $this->offset < $offset; ){

			if(!next($this->array)) {
				throw new OutOfBoundsException('invalid seek position: '.$offset);
			}

			$this->offset++;
		}

	}

	/***************
	 * ArrayAccess *
	 ***************/

	/**
	 * @inheritDoc
	 */
	public function offsetExists($offset):bool{
		return array_key_exists($offset, $this->array);
	}

	/**
	 * @inheritDoc
	 *
	 * @return \DOMNode|\chillerlan\PrototypeDOM\Node\PrototypeHTMLElement|null
	 */
	public function offsetGet($offset):?DOMNode{
		return $this->array[$offset] ?? null;
	}

	/**
	 * @inheritDoc
	 *
	 * @return \DOMNode|\chillerlan\PrototypeDOM\Node\PrototypeHTMLElement|null
	 */
	public function offsetSet($offset, $value):void{

		if($value instanceof DOMNode){

			is_int($offset)
				? $this->array[$offset] = $value
				: $this->array[] = $value;

		}

	}

	/**
	 * @inheritDoc
	 */
	public function offsetUnset($offset):void{
		unset($this->array[$offset]);
	}

	/*************
	 * Prototype *
	 *************/

	/**
	 * Returns the debug-oriented string representation of the object.
	 *
	 * @link http://api.prototypejs.org/language/Enumerable/prototype/inspect/
	 */
	public function inspect(bool $xml = false):string{
		return (new Document($this, $xml))->inspect(null, $xml);
	}

	/**
	 * Returns the array's first item
	 *
	 * @link http://api.prototypejs.org/language/Array/prototype/first/
	 *
	 * @return \DOMNode|\chillerlan\PrototypeDOM\Node\PrototypeHTMLElement|null
	 */
	public function first():?DOMNode{
		return $this->array[0] ?? null;
	}

	/**
	 * Returns the array's last item
	 *
	 * @link http://api.prototypejs.org/language/Array/prototype/last/
	 *
	 * @return \DOMNode|\chillerlan\PrototypeDOM\Node\PrototypeHTMLElement|null
	 */
	public function last():?DOMNode{
		return $this->array[count($this->array) - 1] ?? null;
	}

	/**
	 * Pre-baked implementation for a common use-case of Enumerable#collect and Enumerable#each:
	 * fetching the same property for all of the elements. Returns an array of the property values.
	 *
	 * @link http://api.prototypejs.org/language/Enumerable/prototype/pluck/
	 */
	public function pluck(string $property):array{
		return array_column($this->array, $property);
	}

	/**
	 * Reverses the array's contents.
	 *
	 * @link http://api.prototypejs.org/language/Array/prototype/reverse/
	 */
	public function reverse():NodeList{
		$this->array  = array_reverse($this->array);
		$this->offset = 0;

		return $this;
	}

	/**
	 * Returns an Array containing the elements of the enumeration.
	 *
	 * @link http://api.prototypejs.org/language/Enumerable/prototype/toArray/
	 */
	public function toArray():array{
		return $this->array;
	}

	/**
	 * Calls iterator for each item in the collection.
	 *
	 * @link http://api.prototypejs.org/language/Enumerable/prototype/each/
	 *
	 * @param callable|\Closure $iterator
	 */
	public function each($iterator):NodeList{
		$this->map($iterator);

		return $this;
	}

	/**
	 * Returns the result of applying iterator to each element.
	 *
	 * @link http://api.prototypejs.org/language/Enumerable/prototype/collect/
	 * @link http://api.prototypejs.org/language/Enumerable/prototype/map/
	 *
	 * @param callable|\Closure $iterator
	 * @throws \InvalidArgumentException
	 */
	public function map($iterator):array{

		if(!is_callable($iterator)){
			throw new InvalidArgumentException('invalid iterator');
		}

		$return = [];

		foreach($this->array as $index => $element){
			$return[$index] = call_user_func_array($iterator, [$element, $index]);
		}

		return $return;
	}

	/**
	 * Clears the array (makes it empty) and returns the array reference.
	 *
	 * @link http://api.prototypejs.org/language/Array/prototype/clear/
	 */
	public function clear():NodeList{
		$this->array  = [];
		$this->offset = 0;

		return $this;
	}

	/**
	 * Returns all the elements for which the iterator returned a truthy value.
	 * For the opposite operation, see Enumerable#reject.
	 *
	 * @link http://api.prototypejs.org/language/Enumerable/prototype/findAll/
	 *
	 * @param callable|\Closure $iterator
	 *
	 * @return array<\chillerlan\PrototypeDOM\Node\PrototypeNode|\chillerlan\PrototypeDOM\Node\PrototypeHTMLElement|\DOMNode>
	 * @throws \InvalidArgumentException
	 */
	public function findAll($iterator):array{

		if(!is_callable($iterator)){
			throw new InvalidArgumentException('invalid iterator');
		}

		$return = [];

		foreach($this->array as $index => $element){

			if(call_user_func_array($iterator, [$element, $index]) === true){
				$return[] = $element;
			}

		}

		return $return;
	}

	/**
	 * Returns all the elements for which the iterator returns a falsy value.
	 * For the opposite operation, see Enumerable#findAll.
	 *
	 * @link http://api.prototypejs.org/language/Enumerable/prototype/reject/
	 *
	 * @param callable|\Closure $iterator
	 *
	 * @return array<\chillerlan\PrototypeDOM\Node\PrototypeNode|\chillerlan\PrototypeDOM\Node\PrototypeHTMLElement|\DOMNode>
	 * @throws \InvalidArgumentException
	 */
	public function reject($iterator):array{

		if(!is_callable($iterator)){
			throw new InvalidArgumentException('invalid iterator');
		}

		$return = [];

		foreach($this->array as $index => $element){

			if(call_user_func_array($iterator, [$element, $index]) !== true){
				$return[] = $element;
			}

		}

		return $return;
	}

}
