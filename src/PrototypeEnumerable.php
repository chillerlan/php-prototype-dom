<?php
/**
 * Interface PrototypeEnumerable
 *
 * @filesource   PrototypeEnumerable.php
 * @created      13.01.2018
 * @package      chillerlan\PrototypeDOM
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2018 Smiley
 * @license      MIT
 */

namespace chillerlan\PrototypeDOM;

interface PrototypeEnumerable{

	/**
	 * @link http://api.prototypejs.org/language/Enumerable/prototype/toArray/
	 *
	 * @return array
	 */
	public function toArray():array;

	/**
	 * @link http://api.prototypejs.org/language/Enumerable/prototype/each/
	 *
	 * @param callable|\Closure $callback
	 *
	 * @return mixed
	 */
	public function each($callback);

	/**
	 * @link http://api.prototypejs.org/language/Enumerable/prototype/collect/
	 * @link http://api.prototypejs.org/language/Enumerable/prototype/map/
	 *
	 * @param callable|\Closure $callback
	 *
	 * @return array
	 */
	public function map($callback):array;

	/**
	 * @link http://api.prototypejs.org/language/Array/prototype/reverse/
	 *
	 * @return \chillerlan\PrototypeDOM\PrototypeEnumerable
	 */
	public function reverse():PrototypeEnumerable;

	/**
	 * @link http://api.prototypejs.org/language/Array/prototype/first/
	 *
	 * @return mixed
	 */
	public function first();

	/**
	 * @link http://api.prototypejs.org/language/Array/prototype/last/
	 *
	 * @return mixed
	 */
	public function last();

	/**
	 * @link http://api.prototypejs.org/language/Array/prototype/clear/
	 *
	 * @return \chillerlan\PrototypeDOM\PrototypeEnumerable
	 */
	public function clear():PrototypeEnumerable;

	/**
	 * @link http://api.prototypejs.org/language/Enumerable/prototype/findAll/
	 *
	 * @param callable $callback
	 *
	 * @return array
	 */
	public function findAll($callback):array;

	/**
	 * @link http://api.prototypejs.org/language/Enumerable/prototype/reject/
	 *
	 * @param callable|\Closure $callback
	 *
	 * @return array
	 */
	public function reject($callback):array;

	/**
	 * @link http://api.prototypejs.org/language/Enumerable/prototype/pluck/
	 *
	 * @param string $property
	 *
	 * @return array
	 */
	public function pluck(string $property):array;

}
