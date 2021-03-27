<?php
/**
 * Interface PrototypeNode
 *
 * @created      11.05.2017
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2017 Smiley
 * @license      MIT
 */

namespace chillerlan\PrototypeDOM\Node;

use chillerlan\PrototypeDOM\NodeList;

/**
 * @extends \DOMNode
 */
interface PrototypeNode{

	/**
	 * Recursively collects elements whose relationship to element is specified by property.
	 * property has to be a property of element that points to a single DOM node (e.g., nextSibling or parentNode).
	 *
	 * @link http://api.prototypejs.org/dom/Element/recursivelyCollect/
	 */
	public function recursivelyCollect(string $property, int $maxLength = null, int $nodeType = null):NodeList;

	/**
	 * Tests whether element is empty (i.e., contains only whitespace).
	 *
	 * @link http://api.prototypejs.org/dom/Element/empty/
	 *
	 * @return bool
	 */
	public function empty():bool;

	/**
	 * Returns the debug-oriented string representation of element.
	 *
	 * @link http://api.prototypejs.org/dom/Element/inspect/
	 */
	public function inspect(bool $xml = null):string;

	/**
	 * Completely removes element from the document and returns it.
	 *
	 * @link http://api.prototypejs.org/dom/Element/remove/
	 */
	public function removeNode():PrototypeNode;

	/**
	 * Replaces element itself with newContent and returns element.
	 *
	 * @link http://api.prototypejs.org/dom/Element/replace/
	 */
	public function replace(PrototypeNode $newNode):PrototypeNode;

	/**
	 * Removes all of element's child text nodes that contain only whitespace. Returns element.
	 *
	 * @link http://api.prototypejs.org/dom/Element/cleanWhitespace/
	 */
	public function cleanWhitespace():PrototypeNode;

	/**
	 * Checks if element matches the given CSS selector.
	 *
	 * @link http://api.prototypejs.org/dom/Element/match/
	 */
	public function match(string $selector):bool;

	/**
	 * Removes all child elements from the current element (not to confuse with the prototype method of the same name)
	 */
	public function purge():PrototypeNode;

	/**
	 * Imports a new node into the document
	 */
	public function importNode(PrototypeNode $newNode):PrototypeNode;

	/**
	 * Returns the node name (convenience method)
	 */
	public function name():string;

	/**
	 * Returns the node value, optionally trims whitespace (default)
	 */
	public function value(bool $trimmed = true):string;

}
