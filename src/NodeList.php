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

use DOMNode, DOMNodeList, Iterator, ArrayAccess, Countable;

/**
 * @see http://api.prototypejs.org/language/Enumerable/
 */
class NodeList implements Iterator, ArrayAccess, Countable{

	/**
	 * @var array
	 */
	protected $nodelist = [];

	/**
	 * @var int
	 */
	protected $key = 0;

	/**
	 * NodeList constructor.
	 *
	 * @param \DOMNodeList|\chillerlan\PrototypeDOM\NodeList|\chillerlan\PrototypeDOM\Element[] $content
	 *
	 * @throws \Exception
	 */
	public function __construct($content = null){

		if($content instanceof NodeList || $content instanceof DOMNodeList){
			$this->nodelist = iterator_to_array($content);
		}
		elseif(is_array($content)){
			$this->nodelist = $content;
		}
		else{
			$this->nodelist = [];
		}

	}

	/**
	 * @param \DOMNode $node
	 *
	 * @return bool
	 */
	public function match(DOMNode $node):bool{

		foreach($this->nodelist as $element){

			if($element->isSameNode($node)){
				return true;
			}

		}

		return false;
	}

	/**
	 * @param int $index
	 *
	 * @return \DOMNode|null
	 */
	public function item(int $index) {
		return $this->nodelist[$index] ?? null;
	}

	/**
	 * @return \DOMNode|null
	 */
	public function first(){
		return $this->nodelist[0] ?? null;
	}

	/**
	 * @return \DOMNode|null
	 */
	public function last(){
		return $this->nodelist[$this->count() - 1] ?? null;
	}

	/**
	 * @param string $name
	 *
	 * @return array
	 */
	public function pluck(string $name):array {
		return array_column($this->nodelist, $name);
	}

	/**
	 * @return \chillerlan\PrototypeDOM\NodeList
	 */
	public function reverse():NodeList{
		$this->nodelist = array_reverse($this->nodelist);
		$this->rewind();

		return $this;
	}

	/**
	 * @return array
	 */
	public function toArray():array {
		return $this->nodelist;
	}

	/**
	 * @param \chillerlan\PrototypeDOM\NodeList $nodelist
	 *
	 * @return \chillerlan\PrototypeDOM\NodeList
	 */
	public function merge(NodeList $nodelist):NodeList{
		$this->nodelist = array_merge($this->nodelist, $nodelist->toArray());

		return $this;
	}


	/************
	 * Iterator *
	 ************/

	/**
	 * @return \DOMNode
	 */
	public function current():DOMNode{
		return $this->nodelist[$this->key];
	}

	/**
	 * @return int
	 */
	public function key():int{
		return $this->key;
	}

	/**
	 * @return bool
	 */
	public function valid():bool{
		return $this->offsetExists($this->key);
	}

	/**
	 *  @return void
	 */
	public function next(){
		$this->key++;
	}

	/**
	 * @return void
	 */
	public function rewind(){
		$this->key = 0;
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
		return isset($this->nodelist[$offset]);
	}

	/**
	 * @param int $offset
	 *
	 * @return \DOMNode|null
	 */
	public function offsetGet($offset){
		return $this->item($offset);
	}

	/**
	 * @param int   $offset
	 * @param mixed $value
	 *
	 * @return void
	 */
	public function offsetSet($offset, $value){

		if(is_int($offset)){
			$this->nodelist[$offset] = $value;
		}
		else{
			$this->nodelist[] = $value;
		}

	}

	/**
	 * @param int $offset
	 *
	 * @return void
	 */
	public function offsetUnset($offset){
		unset($this->nodelist[$offset]);
	}


	/*************
	 * Countable *
	 *************/

	/**
	 * @return int
	 */
	public function count():int{
		return count($this->nodelist);
	}

}
