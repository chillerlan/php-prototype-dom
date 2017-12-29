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
use chillerlan\PrototypeDOM\{
	Node\PrototypeNode, Traits\EnumerableTrait
};
use chillerlan\Traits\{
	Magic, Interfaces\ArrayAccessTrait, SPL\CountableTrait, SPL\SeekableIteratorTrait
};

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
 * @method
 */
class NodeList implements Iterator, ArrayAccess, Countable{
	use EnumerableTrait, Magic, SeekableIteratorTrait, ArrayAccessTrait, CountableTrait;

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

	/***************
	 * ArrayAccess *
	 ***************/

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

}
