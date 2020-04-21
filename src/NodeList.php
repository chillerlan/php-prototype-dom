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

use chillerlan\PrototypeDOM\Node\PrototypeNode;
use ArrayAccess, Countable, DOMNode, DOMNodeList, Exception, OutOfBoundsException, SeekableIterator;

use function array_column, array_key_exists, array_merge, array_reverse, call_user_func_array, count,
	is_callable, is_int, is_iterable, iterator_to_array;


class NodeList implements PrototypeEnumerable, SeekableIterator, ArrayAccess, Countable{

	protected array $array = [];

	protected int $offset = 0;

	/**
	 * NodeList constructor.
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
	 *
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
	 *
	 */
	public function merge(NodeList $nodelist):NodeList{
		$this->array = array_merge($this->array, $nodelist->toArray());

		return $this;
	}

	/**
	 *
	 */
	public function inspect(bool $xml = false):string{
		return (new Document($this, $xml))->inspect(null, $xml);
	}

	/*************
	 * Countable *
	 *************/

	/**
	 * @link https://www.php.net/manual/countable.count.php
	 * @inheritdoc
	 */
	public function count():int{
		return count($this->array);
	}

	/************
	 * Iterator *
	 ************/

	/**
	 * @link https://www.php.net/manual/iterator.current.php
	 * @inheritdoc
	 * @return \DOMNode|\chillerlan\PrototypeDOM\Node\PrototypeHTMLElement|null
	 */
	public function current():?DOMNode{
		return $this->array[$this->offset] ?? null;
	}

	/**
	 * @link https://www.php.net/manual/iterator.next.php
	 * @inheritdoc
	 */
	public function next():void{
		$this->offset++;
	}

	/**
	 * @link https://www.php.net/manual/iterator.key.php
	 * @inheritdoc
	 */
	public function key():int{
		return $this->offset;
	}

	/**
	 * @link https://www.php.net/manual/iterator.valid.php
	 * @inheritdoc
	 */
	public function valid():bool{
		return array_key_exists($this->offset, $this->array);
	}

	/**
	 * @link https://www.php.net/manual/iterator.rewind.php
	 * @inheritdoc
	 */
	public function rewind():void{
		$this->offset = 0;
	}

	/********************
	 * SeekableIterator *
	 ********************/

	/**
	 * @link https://www.php.net/manual/seekableiterator.seek.php
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
	 * @link https://www.php.net/manual/arrayaccess.offsetexists.php
	 * @inheritdoc
	 */
	public function offsetExists($offset):bool{
		return array_key_exists($offset, $this->array);
	}

	/**
	 * @link https://www.php.net/manual/arrayaccess.offsetget.php
	 * @inheritdoc
	 * @return \DOMNode|\chillerlan\PrototypeDOM\Node\PrototypeHTMLElement|null
	 */
	public function offsetGet($offset):?DOMNode{
		return $this->array[$offset] ?? null;
	}

	/**
	 * @link https://www.php.net/manual/arrayaccess.offsetset.php
	 * @inheritdoc
	 */
	public function offsetSet($offset, $value):void{

		if($value instanceof DOMNode){

			is_int($offset)
				? $this->array[$offset] = $value
				: $this->array[] = $value;

		}

	}

	/**
	 * @link https://www.php.net/manual/arrayaccess.offsetunset.php
	 * @inheritdoc
	 */
	public function offsetUnset($offset):void{
		unset($this->array[$offset]);
	}

	/*************
	 * Prototype *
	 *************/

	/**
	 * @inheritDoc
	 * @return \DOMNode|\chillerlan\PrototypeDOM\Node\PrototypeHTMLElement|null
	 */
	public function first():?DOMNode{
		return $this->array[0] ?? null;
	}

	/**
	 * @inheritDoc
	 * @return \DOMNode|\chillerlan\PrototypeDOM\Node\PrototypeHTMLElement|null
	 */
	public function last():?DOMNode{
		return $this->array[count($this->array) - 1] ?? null;
	}

	/**
	 * @inheritDoc
	 */
	public function pluck(string $property):array{
		return array_column($this->array, $property);
	}

	/**
	 * @inheritDoc
	 */
	public function reverse():NodeList{
		$this->array  = array_reverse($this->array);
		$this->offset = 0;

		return $this;
	}

	/**
	 * @inheritDoc
	 */
	public function toArray():array{
		return $this->array;
	}

	/**
	 * @inheritDoc
	 */
	public function each($callback){
		$this->map($callback);

		return $this;
	}

	/**
	 * @inheritDoc
	 */
	public function map($callback):array {

		if(!is_callable($callback)){
			throw new Exception('invalid callback');
		}

		$return = [];

		foreach($this->array as $index => $element){
			$return[$index] = call_user_func_array($callback, [$element, $index]);
		}

		return $return;
	}

	/**
	 * @inheritDoc
	 */
	public function clear():NodeList{
		$this->array  = [];
		$this->offset = 0;

		return $this;
	}

	/**
	 * @inheritDoc
	 * @throws \Exception
	 */
	public function findAll($callback):array{

		if(!is_callable($callback)){
			throw new Exception('invalid callback');
		}

		$return = [];

		foreach($this->array as $index => $element){

			if(call_user_func_array($callback, [$element, $index]) === true){
				$return[] = $element;
			}

		}

		return $return;
	}

	/**
	 * @inheritDoc
	 * @throws \Exception
	 */
	public function reject($callback):array{

		if(!is_callable($callback)){
			throw new Exception('invalid callback');
		}

		$return = [];

		foreach($this->array as $index => $element){

			if(call_user_func_array($callback, [$element, $index]) !== true){
				$return[] = $element;
			}

		}

		return $return;
	}

}
