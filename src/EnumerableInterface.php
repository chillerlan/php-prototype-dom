<?php
/**
 * Interface EnumerableInterface
 *
 * @filesource   EnumerableInterface.php
 * @created      13.01.2018
 * @package      chillerlan\PrototypeDOM
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2018 Smiley
 * @license      MIT
 */

namespace chillerlan\PrototypeDOM;

use ArrayAccess, Countable, SeekableIterator;

interface EnumerableInterface extends SeekableIterator, ArrayAccess, Countable{

	/**
	 * @return array
	 */
	public function toArray():array;

	/**
	 * @param callable $callback
	 *
	 * @return mixed
	 */
	public function each($callback);

	/**
	 * @param callable $callback
	 *
	 * @return array
	 */
	public function map($callback):array;

	/**
	 * @return \chillerlan\PrototypeDOM\EnumerableInterface
	 */
	public function reverse():EnumerableInterface;

	/**
	 * @return mixed
	 */
	public function first();

	/**
	 * @return mixed
	 */
	public function last();

	/**
	 * @return \chillerlan\PrototypeDOM\EnumerableInterface
	 */
	public function clear():EnumerableInterface;

	/**
	 * @param callable $callback
	 *
	 * @return array
	 */
	public function findAll($callback):array;

	/**
	 * @param callable $callback
	 *
	 * @return array
	 */
	public function reject($callback):array;

}
