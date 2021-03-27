<?php
/**
 * Interface PrototypeElement
 *
 * @filesource   PrototypeElement.php
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
interface PrototypeElement extends PrototypeTraversal{

	/**
	 * @link http://api.prototypejs.org/dom/Element/wrap/
	 */
	public function wrap(PrototypeElement $wrapper):PrototypeElement;

	/**
	 * @link http://api.prototypejs.org/dom/Element/update/
	 *
	 * @param string|\DOMNode|\DOMNodeList $content
	 *
	 * @return \chillerlan\PrototypeDOM\Node\PrototypeElement
	 */
	public function update($content):PrototypeElement;

	/**
	 * @link http://api.prototypejs.org/dom/Element/insert/
	 *
	 * Accepted insertion points are:
	 * - before (as element's previous sibling)
	 * - after (as element's next sibling)
	 * - top (as element's first child)
	 * - bottom (as element's last child)
	 *
	 * @param string|array|\DOMNode|\DOMNodeList|\chillerlan\PrototypeDOM\Node\PrototypeElement $content
	 *
	 * @return \chillerlan\PrototypeDOM\Node\PrototypeElement
	 */
	public function insert($content):PrototypeElement;

	/**
	 * Shortcut for PrototypeElement::insert(['before' => $content])
	 */
	public function insert_before(PrototypeElement $node, PrototypeElement $refNode = null):PrototypeElement;

	/**
	 * Shortcut for PrototypeElement::insert(['after' => $content])
	 */
	public function insert_after(PrototypeElement $node):PrototypeElement;

	/**
	 * Shortcut for PrototypeElement::insert(['top' => $content])
	 */
	public function insert_top(PrototypeElement $node):PrototypeElement;

	/**
	 * Shortcut for PrototypeElement::insert(['bottom' => $content])
	 */
	public function insert_bottom(PrototypeElement $node):PrototypeElement;

	/**
	 * Returns the element tag name (convenience method)
	 */
	public function tag():string;
}
