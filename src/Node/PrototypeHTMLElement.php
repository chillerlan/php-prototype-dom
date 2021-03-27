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
 * @extends \DOMElement
 */
interface PrototypeHTMLElement extends PrototypeElement{

	/**
	 * Returns the (raw) classname
	 */
	public function getClassName():string;

	/**
	 * Sets the (raw) classname
	 */
	public function setClassName(string $class):PrototypeHTMLElement;

	/**
	 * Returns the "href" attribute (if any, convenience)
	 */
	public function getHref():string;

	/**
	 * Sets the "href" attribute (convenience)
	 */
	public function setHref(string $href):PrototypeHTMLElement;

	/**
	 * Returns the "src" attribute (if any, convenience)
	 */
	public function getSrc():string;

	/**
	 * Sets the "src" attribute (convenience)
	 */
	public function setSrc(string $src):PrototypeHTMLElement;

	/*************
	 * Prototype *
	 *************/

	/**
	 * Returns element's ID. If element does not have an ID, $newID assigned to element, and the old id returned.
	 *
	 * http://api.prototypejs.org/dom/Element/identify/
	 */
	public function identify(string $newID = null):string;

	/**
	 * Returns an array of the element's current class names.
	 *
	 * @link http://api.prototypejs.org/dom/Element/classNames/
	 *
	 * @return string[]
	 */
	public function classNames():array;

	/**
	 * Checks for the presence of CSS class className on element.
	 *
	 * @link http://api.prototypejs.org/dom/Element/hasClassName/
	 */
	public function hasClassName(string $classname):bool;

	/**
	 *Adds the given CSS class to element.
	 *
	 * @link http://api.prototypejs.org/dom/Element/addClassName/
	 */
	public function addClassName(string $classname):PrototypeHTMLElement;

	/**
	 * Removes CSS class className from element.
	 *
	 * @link http://api.prototypejs.org/dom/Element/removeClassName/
	 */
	public function removeClassName(string $classname):PrototypeHTMLElement;

	/**
	 * Toggles the presence of CSS class className on element.
	 *
	 * @link http://api.prototypejs.org/dom/Element/toggleClassName/
	 */
	public function toggleClassName(string $classname):PrototypeHTMLElement;

	/**
	 * Returns the given CSS property value of element.
	 *
	 * @link http://api.prototypejs.org/dom/Element/getStyle/
	 */
	public function getStyle(string $property):?string;

	/**
	 * Modifies element's CSS style properties.
	 *
	 * @link http://api.prototypejs.org/dom/Element/setStyle/
	 */
	public function setStyle(array $style, bool $replace = null):PrototypeHTMLElement;

	/***********
	 * Generic *
	 ***********/

	/**
	 * @return  string[]
	 */
	public function getAttributes():array;

	/**
	 * @param array $attributes
	 *
	 * @return \chillerlan\PrototypeDOM\Node\PrototypeHTMLElement
	 */
	public function setAttributes(array $attributes):PrototypeHTMLElement;

	/**
	 * @param array $attributes
	 *
	 * @return \chillerlan\PrototypeDOM\Node\PrototypeHTMLElement
	 */
	public function removeAttributes(array $attributes):PrototypeHTMLElement;

	/**
	 * @param array $classnames
	 *
	 * @return \chillerlan\PrototypeDOM\Node\PrototypeHTMLElement
	 */
	public function addClassNames(array $classnames):PrototypeHTMLElement;

	/**
	 * @param array $classnames
	 *
	 * @return \chillerlan\PrototypeDOM\Node\PrototypeHTMLElement
	 */
	public function removeClassNames(array $classnames):PrototypeHTMLElement;

	/**
	 * @return string[]
	 */
	public function getStyles():array;

}
