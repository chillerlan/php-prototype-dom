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

interface PrototypeTraversal extends PrototypeNode{

	/**
	 * @link http://api.prototypejs.org/dom/Element/select/
	 */
	public function select(array $selectors = null):NodeList;

	/**
	 * @link http://api.prototypejs.org/dom/Element/down/
	 *
	 * @param int|string|array|null $expression
	 * @param int  $index
	 *
	 * @return \chillerlan\PrototypeDOM\Node\PrototypeTraversal|null
	 */
	public function down($expression = null, int $index = null):?PrototypeTraversal;

	/**
	 * @link http://api.prototypejs.org/dom/Element/up/
	 *
	 * @param int|string|array|null $expression
	 * @param int|null    $index
	 *
	 * @return \chillerlan\PrototypeDOM\Node\PrototypeTraversal|null
	 */
	public function up($expression = null, int $index = null):?PrototypeTraversal;

	/**
	 * @link http://api.prototypejs.org/dom/Element/previous/
	 *
	 * @param int|string|array|null $expression
	 * @param int|null    $index
	 *
	 * @return \chillerlan\PrototypeDOM\Node\PrototypeTraversal|null
	 */
	public function previous($expression = null, int $index = null):?PrototypeTraversal;

	/**
	 * @link http://api.prototypejs.org/dom/Element/next/
	 *
	 * @param int|string|array|null $expression
	 * @param int|null    $index
	 *
	 * @return \chillerlan\PrototypeDOM\Node\PrototypeTraversal|null
	 */
	public function next($expression = null, int $index = null):?PrototypeTraversal;

	/**
	 * @link http://api.prototypejs.org/dom/Element/childElements/
	 * @see https://secure.php.net/manual/dom.constants.php
	 */
	public function childElements(int $nodeType = null):NodeList;

	/**
	 * @link http://api.prototypejs.org/dom/Element/descendantOf/
	 */
	public function descendantOf(DOMNode $ancestor):bool;

	/**
	 * @link http://api.prototypejs.org/dom/Element/ancestors/
	 */
	public function ancestors():NodeList;

	/**
	 * @link http://api.prototypejs.org/dom/Element/siblings/
	 */
	public function siblings():NodeList;

	/**
	 * @link http://api.prototypejs.org/dom/Element/descendants/
	 */
	public function descendants():NodeList;

	/**
	 * @link http://api.prototypejs.org/dom/Element/firstDescendant/
	 */
	public function firstDescendant():?PrototypeTraversal;

	/**
	 * @link http://api.prototypejs.org/dom/Element/previousSiblings/
	 */
	public function previousSiblings():NodeList;

	/**
	 * @link http://api.prototypejs.org/dom/Element/nextSiblings/
	 */
	public function nextSiblings():NodeList;

}
