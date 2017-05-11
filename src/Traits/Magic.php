<?php
/**
 *
 * @filesource   Magic.php
 * @created      11.05.2017
 * @package      chillerlan\PrototypeDOM\Traits
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2017 Smiley
 * @license      MIT
 */

namespace chillerlan\PrototypeDOM\Traits;

/**
 * Trait Magic
 */
trait Magic{

	public function __get($name) {
		return $this->get($name);
	}

	public function __set($name, $value) {
		$this->set($name, $value);
	}

	private function get($name) {
		$method = 'magic_get_'.$name;

		return method_exists($this, $method) ? $this->$method() : null;
	}

	private function set($name, $value) {
		$method = 'magic_set_'.$name;

		if(method_exists($this, $method)){
			$this->$method($value);
		}

	}

}
