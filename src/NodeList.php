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

use ArrayAccess, Countable, DOMNodeList, Iterator;
use chillerlan\PrototypeDOM\Node\PrototypeNode;
use chillerlan\PrototypeDOM\Traits\{EnumerableTrait, Magic};

/**
 * @property int $length
 */
class NodeList implements Iterator, ArrayAccess, Countable{
	use EnumerableTrait, Magic;

	/**
	 * @var array
	 */
	protected $array = [];

	/**
	 * @var int
	 */
	protected $offset = 0;

	/**
	 * NodeList constructor.
	 *
	 * @param \DOMNodeList $nodes
	 *
	 * @throws \Exception
	 */
	public function __construct(DOMNodeList $nodes = null){

		if(!is_null($nodes)){
			$this->array = iterator_to_array($nodes);
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
	 * @param \chillerlan\PrototypeDOM\Node\PrototypeNode $node
	 *
	 * @return bool
	 */
	public function match(PrototypeNode $node):bool{

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
	 * @return \chillerlan\PrototypeDOM\Node\Element|null
	 */
	public function item(int $offset) {
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
	public function inspect($xml = false):string {
		return (new Document($this, $xml))->inspect(null, $xml);
	}


	/************
	 * Iterator *
	 ************/

	/**
	 * @return \chillerlan\PrototypeDOM\Node\PrototypeNode
	 */
	public function current(){
		return $this->array[$this->offset];
	}

	/**
	 * @return int
	 */
	public function key():int{
		return $this->offset;
	}

	/**
	 * @return bool
	 */
	public function valid():bool{
		return $this->offsetExists($this->offset);
	}

	/**
	 *  @return void
	 */
	public function next(){
		$this->offset++;
	}

	/**
	 * @return void
	 */
	public function rewind(){
		$this->offset = 0;
	}


	/***************
	 * ArrayAccess *
	 ***************/

	/**
	 * @param int $offset
	 *
	 * @return bool
	 */
	public function offsetExists($offset):bool{
		return isset($this->array[$offset]);
	}

	/**
	 * @param int $offset
	 *
	 * @return \chillerlan\PrototypeDOM\Node\PrototypeNode|null
	 */
	public function offsetGet($offset){
		return $this->item($offset);
	}

	/**
	 * @param int                                         $offset
	 * @param \chillerlan\PrototypeDOM\Node\PrototypeNode $value
	 *
	 * @return void
	 */
	public function offsetSet($offset, $value){

		if($value instanceof PrototypeNode){

			if(is_int($offset)){
				$this->array[$offset] = $value;
			}
			else{
				$this->array[] = $value;
			}

		}

	}

	/**
	 * @param int $offset
	 *
	 * @return void
	 */
	public function offsetUnset($offset){
		unset($this->array[$offset]);
	}


	/*************
	 * Countable *
	 *************/

	/**
	 * @return int
	 */
	public function count():int{
		return count($this->array);
	}

}
