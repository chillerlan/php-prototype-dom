<?php
/**
 * Interface PrototypeHTMLElement
 *
 * @filesource   PrototypeHTMLElement.php
 * @created      11.05.2017
 * @package      chillerlan\PrototypeDOM\Node
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2017 Smiley
 * @license      MIT
 */

namespace chillerlan\PrototypeDOM\Node;

/**
 *
 */
interface PrototypeHTMLElement extends PrototypeElement{

	public function getID():string;
	public function setID(string $id):PrototypeHTMLElement;
	public function getClassName():string;
	public function setClassName(string $class):PrototypeHTMLElement;
	public function getHref():string;
	public function setHref(string $href):PrototypeHTMLElement;
	public function getSrc():string;
	public function setSrc(string $src):PrototypeHTMLElement;

	/**
	 * http://api.prototypejs.org/dom/Element/identify/
	 *
	 * @param string|null $newID
	 *
	 * @return string
	 */
	public function identify(string $newID = null):string;

	/**
	 * @link http://api.prototypejs.org/dom/Element/classNames/
	 *
	 * @return array
	 */
	public function classNames():array;

	/**
	 * @link http://api.prototypejs.org/dom/Element/hasClassName/
	 *
	 * @param string $classname
	 *
	 * @return bool
	 */
	public function hasClassName(string $classname):bool;

	/**
	 * @link http://api.prototypejs.org/dom/Element/addClassName/
	 *
	 * @param string $classname
	 *
	 * @return \chillerlan\PrototypeDOM\Node\PrototypeHTMLElement
	 */
	public function addClassName(string $classname):PrototypeHTMLElement;

	/**
	 * @link http://api.prototypejs.org/dom/Element/removeClassName/
	 *
	 * @param string $classname
	 *
	 * @return \chillerlan\PrototypeDOM\Node\PrototypeHTMLElement
	 */
	public function removeClassName(string $classname):PrototypeHTMLElement;

	/**
	 * @link http://api.prototypejs.org/dom/Element/toggleClassName/
	 *
	 * @param string $classname
	 *
	 * @return \chillerlan\PrototypeDOM\Node\PrototypeHTMLElement
	 */
	public function toggleClassName(string $classname):PrototypeHTMLElement;

	/**
	 * @link http://api.prototypejs.org/dom/Element/getStyle/
	 *
	 * @param string $property
	 *
	 * @return null|string
	 */
	public function getStyle(string $property):?string;

	/**
	 * @link http://api.prototypejs.org/dom/Element/setStyle/
	 *
	 * @param array $style
	 * @param bool  $replace
	 *
	 * @return \chillerlan\PrototypeDOM\Node\PrototypeHTMLElement
	 */
	public function setStyle(array $style, bool $replace = null):PrototypeHTMLElement;

}
