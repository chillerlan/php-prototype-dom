<?php
/**
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
 * Class NodeList
 */
class NodeList implements Iterator, ArrayAccess, Countable{

	protected $nodelist = [];
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
/*		else{
			throw new \Exception('invalid content'); // @codeCoverageIgnore
		}*/

	}

	public function match(DOMNode $node):bool{

		foreach($this->nodelist as $element){

			if($element->isSameNode($node)){
				return true;
			}

		}

		return false;
	}

	public function item(int $index) {
		return $this->nodelist[$index] ?? null;
	}

	public function reverse():NodeList{
		$this->nodelist = array_reverse($this->nodelist);
		$this->rewind();

		return $this;
	}

	public function _toArray():array {
		return $this->nodelist;
	}

	public function merge(NodeList $nodelist):NodeList{
		$this->nodelist = array_merge($this->nodelist, $nodelist->_toArray());

		return $this;
	}


	public function current():DOMNode{
		return $this->nodelist[$this->key];
	}

	public function key():int{
		return $this->key;
	}

	public function valid():bool{
		return isset($this->nodelist[$this->key]);
	}

	public function next(){
		$this->key++;
	}

	public function rewind(){
		$this->key = 0;
	}


	public function offsetExists($offset):bool{
		return isset($this->nodelist[$offset]);
	}

	public function offsetGet($offset){
		return $this->nodelist[$offset] ?? null;
	}

	public function offsetSet($offset, $value){

		if($this->offsetExists($offset)){
			$this->nodelist[$offset] = $value;
		}
		else{
			$this->nodelist[] = $value;
		}
	}

	public function offsetUnset($offset){
		unset($this->nodelist[$offset]);
	}


	public function count():int{
		return count($this->nodelist);
	}
}
