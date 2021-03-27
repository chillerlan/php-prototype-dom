<?php
/**
 * Interface PrototypeTraversal
 *
 * @filesource   PrototypeTraversal.php
 * @created      11.05.2017
 * @package      chillerlan\PrototypeDOM\Node
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2017 Smiley
 * @license      MIT
 */

namespace chillerlan\PrototypeDOM\Node;

use chillerlan\PrototypeDOM\NodeList;
use DOMNode;
use const XML_ELEMENT_NODE;

/**
 * @extends \DOMNode
 */
interface PrototypeTraversal extends PrototypeNode{

	/**
	 * This method is very similar to $$ but can be used within the context of one element, rather than the whole document.
	 * The supported CSS syntax is identical, so please refer to the $$ docs for details.
	 *
	 * @link http://api.prototypejs.org/dom/Element/select/
	 */
	public function select(array $selectors = null):NodeList;

	/**
	 * Returns element's first descendant (or the Nth descendant, if index is specified) that matches expression.
	 * If no expression is provided, all descendants are considered.
	 * If no descendant matches these criteria, null is returned.
	 *
	 * @link http://api.prototypejs.org/dom/Element/down/
	 *
	 * @param int|string|array|null $expression
	 * @param int|null              $index
	 *
	 * @return \chillerlan\PrototypeDOM\Node\PrototypeTraversal|null
	 */
	public function down($expression = null, int $index = null):?PrototypeTraversal;

	/**
	 * Returns element's first ancestor (or the Nth ancestor, if index is specified) that matches expression.
	 * If no expression is provided, all ancestors are considered.
	 * If no ancestor matches these criteria, null is returned.
	 *
	 * @link http://api.prototypejs.org/dom/Element/up/
	 *
	 * @param int|string|array|null $expression
	 * @param int|null              $index
	 *
	 * @return \chillerlan\PrototypeDOM\Node\PrototypeTraversal|null
	 */
	public function up($expression = null, int $index = null):?PrototypeTraversal;

	/**
	 * Returns element's first previous sibling (or the Nth, if index is specified) that matches expression.
	 * If no expression is provided, all previous siblings are considered.
	 * If none matches these criteria, null is returned.
	 *
	 * @link http://api.prototypejs.org/dom/Element/previous/
	 *
	 * @param int|string|array|null $expression
	 * @param int|null              $index
	 *
	 * @return \chillerlan\PrototypeDOM\Node\PrototypeTraversal|null
	 */
	public function previous($expression = null, int $index = null):?PrototypeTraversal;

	/**
	 * Returns element's first following sibling (or the Nth, if index is specified) that matches expression.
	 * If no expression is provided, all following siblings are considered.
	 * If none matches these criteria, null is returned.
	 *
	 * @link http://api.prototypejs.org/dom/Element/next/
	 *
	 * @param int|string|array|null $expression
	 * @param int|null              $index
	 *
	 * @return \chillerlan\PrototypeDOM\Node\PrototypeTraversal|null
	 */
	public function next($expression = null, int $index = null):?PrototypeTraversal;

	/**
	 * Collects all of the element's children and returns them as an array of Element.extended elements, in document order.
	 * The first entry in the array is the topmost child of element, the next is the child after that, etc.
	 *
	 * @link http://api.prototypejs.org/dom/Element/childElements/
	 * @see https://secure.php.net/manual/dom.constants.php
	 */
	public function childElements(int $nodeType = null):NodeList;

	/**
	 * Checks if element is a descendant of ancestor.
	 *
	 * @link http://api.prototypejs.org/dom/Element/descendantOf/
	 */
	public function descendantOf(DOMNode $ancestor):bool;

	/**
	 * Collects all of element's ancestor elements and returns them as an array of extended elements.
	 *
	 * @link http://api.prototypejs.org/dom/Element/ancestors/
	 */
	public function ancestors():NodeList;

	/**
	 * Collects all of element's siblings and returns them as an Array of elements.
	 *
	 * @link http://api.prototypejs.org/dom/Element/siblings/
	 */
	public function siblings():NodeList;

	/**
	 * Collects all of the element's descendants (its children, their children, etc.)
	 * and returns them as an array of extended elements.
	 *
	 * @link http://api.prototypejs.org/dom/Element/descendants/
	 */
	public function descendants():NodeList;

	/**
	 * Returns the first child that is an element.
	 *
	 * @link http://api.prototypejs.org/dom/Element/firstDescendant/
	 */
	public function firstDescendant():?PrototypeTraversal;

	/**
	 * Collects all of element's previous siblings and returns them as an Array of elements.
	 *
	 * @link http://api.prototypejs.org/dom/Element/previousSiblings/
	 */
	public function previousSiblings():NodeList;

	/**
	 * Collects all of element's next siblings and returns them as an Array of elements.
	 *
	 * @link http://api.prototypejs.org/dom/Element/nextSiblings/
	 */
	public function nextSiblings():NodeList;

	/**
	 * @see https://secure.php.net/manual/dom.constants.php
	 *
	 * @return \chillerlan\PrototypeDOM\Node\PrototypeTraversal|\DOMNode|null
	 */
	public function recursivelyFind(
		string $selector = null,
		int $index = null,
		string $property = null,
		int $nodeType = XML_ELEMENT_NODE
	):?PrototypeTraversal;
}
