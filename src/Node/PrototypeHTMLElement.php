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
	 */
	public function identify(string $newID = null):string;

	/**
	 * @link http://api.prototypejs.org/dom/Element/classNames/
	 *
	 * @return string[]
	 */
	public function classNames():array;

	/**
	 * @link http://api.prototypejs.org/dom/Element/hasClassName/
	 */
	public function hasClassName(string $classname):bool;

	/**
	 * @link http://api.prototypejs.org/dom/Element/addClassName/
	 */
	public function addClassName(string $classname):PrototypeHTMLElement;

	/**
	 * @link http://api.prototypejs.org/dom/Element/removeClassName/
	 */
	public function removeClassName(string $classname):PrototypeHTMLElement;

	/**
	 * @link http://api.prototypejs.org/dom/Element/toggleClassName/
	 */
	public function toggleClassName(string $classname):PrototypeHTMLElement;

	/**
	 * @link http://api.prototypejs.org/dom/Element/getStyle/
	 */
	public function getStyle(string $property):?string;

	/**
	 * @link http://api.prototypejs.org/dom/Element/setStyle/
	 */
	public function setStyle(array $style, bool $replace = null):PrototypeHTMLElement;

}
