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

interface PrototypeTraversal extends PrototypeNode{

	/**
	 * @link http://api.prototypejs.org/dom/Element/select/
	 *
	 * @param string|array $selectors
	 *
	 * @return \chillerlan\PrototypeDOM\NodeList
	 */
	public function select($selectors = null):NodeList;

	/**
	 * @link http://api.prototypejs.org/dom/Element/match/
	 *
	 * @param string $selector
	 *
	 * @return bool
	 */
	public function match(string $selector):bool;

	/**
	 * @link http://api.prototypejs.org/dom/Element/down/
	 *
	 * @param null $expression
	 * @param int  $index
	 *
	 * @return \chillerlan\PrototypeDOM\Node\Element|null
	 */
	public function down($expression = null, int $index = null);

	/**
	 * @link http://api.prototypejs.org/dom/Element/up/
	 *
	 * @param string|null $expression
	 * @param int|null    $index
	 *
	 * @return \chillerlan\PrototypeDOM\Node\Element|null
	 */
	public function up($expression = null, int $index = null);

	/**
	 * @link http://api.prototypejs.org/dom/Element/previous/
	 *
	 * @param string|null $expression
	 * @param int|null    $index
	 *
	 * @return \chillerlan\PrototypeDOM\Node\Element|null
	 */
	public function previous($expression = null, int $index = null);

	/**
	 * @link http://api.prototypejs.org/dom/Element/next/
	 *
	 * @param string|null $expression
	 * @param int|null    $index
	 *
	 * @return \chillerlan\PrototypeDOM\Node\Element|null
	 */
	public function next($expression = null, int $index = null);

	/**
	 * @link http://api.prototypejs.org/dom/Element/childElements/
	 *
	 * @param int $nodeType
	 *
	 * @return \chillerlan\PrototypeDOM\NodeList
	 */
	public function childElements(int $nodeType = XML_ELEMENT_NODE):NodeList;

	/**
	 * @link http://api.prototypejs.org/dom/Element/descendantOf/
	 *
	 * @param \chillerlan\PrototypeDOM\Node\PrototypeNode $ancestor
	 *
	 * @return bool
	 */
	public function descendantOf(PrototypeNode $ancestor):bool;

	/**
	 * @link http://api.prototypejs.org/dom/Element/ancestors/
	 *
	 * @return \chillerlan\PrototypeDOM\NodeList
	 */
	public function ancestors():NodeList;

	/**
	 * @link http://api.prototypejs.org/dom/Element/siblings/
	 *
	 * @return \chillerlan\PrototypeDOM\NodeList
	 */
	public function siblings():NodeList;

	/**
	 * @link http://api.prototypejs.org/dom/Element/descendants/
	 *
	 * @return \chillerlan\PrototypeDOM\NodeList
	 */
	public function descendants():NodeList;

	/**
	 * @link http://api.prototypejs.org/dom/Element/firstDescendant/
	 *
	 * @return \chillerlan\PrototypeDOM\Node\Element|null
	 */
	public function firstDescendant();

	/**
	 * @link http://api.prototypejs.org/dom/Element/previousSiblings/
	 *
	 * @return \chillerlan\PrototypeDOM\NodeList
	 */
	public function previousSiblings():NodeList;

	/**
	 * @link http://api.prototypejs.org/dom/Element/nextSiblings/
	 *
	 * @return \chillerlan\PrototypeDOM\NodeList
	 */
	public function nextSiblings():NodeList;

}
