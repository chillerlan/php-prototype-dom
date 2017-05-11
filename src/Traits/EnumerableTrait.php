<?php
/**
 *
 * @filesource   EnumerableTrait.php
 * @created      11.05.2017
 * @package      chillerlan\PrototypeDOM\Traits
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2017 Smiley
 * @license      MIT
 */

namespace chillerlan\PrototypeDOM\Traits;

/**
 * Trait EnumerableTrait
 */
trait EnumerableTrait{

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
	 * @link http://api.prototypejs.org/language/Array/prototype/first/
	 *
	 * @return \chillerlan\PrototypeDOM\Node\Element|null
	 */
	public function first(){
		return $this->item(0);
	}

	/**
	 * @link http://api.prototypejs.org/language/Array/prototype/last/
	 *
	 * @return \chillerlan\PrototypeDOM\Node\Element|null
	 */
	public function last(){
		return $this->item($this->count() - 1);
	}

	/**
	 * @link http://api.prototypejs.org/language/Enumerable/prototype/collect/
	 * @link http://api.prototypejs.org/language/Enumerable/prototype/map/
	 *
	 * @param $callback
	 *
	 * @return array
	 * @throws \Exception
	 */
	public function map($callback):array {

		if(!is_callable($callback)){
			throw new \Exception('invalid callback'); // @codeCoverageIgnore
		}

		$return = [];

		foreach($this->array as $index => $element){
			$return[$index] = call_user_func_array($callback, [$element, $index]);
		}

		return $return;
	}

	/**
	 * @link http://api.prototypejs.org/language/Enumerable/prototype/pluck/
	 *
	 * @param string $property
	 *
	 * @return array
	 */
	public function pluck(string $property):array {
		return array_column($this->array, $property);
	}

	/**
	 * @link http://api.prototypejs.org/language/Array/prototype/reverse/
	 *
	 * @return $this
	 */
	public function reverse(){
		$this->array = array_reverse($this->array);
		$this->rewind();

		return $this;
	}

	/**
	 * @link http://api.prototypejs.org/language/Enumerable/prototype/toArray/
	 *
	 * @return array
	 */
	public function toArray():array {
		return $this->array;
	}

}
