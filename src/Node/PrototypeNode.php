<?php
/**
 * Interface PrototypeNode
 *
 * @filesource   PrototypeNode.php
 * @created      11.05.2017
 * @package      chillerlan\PrototypeDOM\Node
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2017 Smiley
 * @license      MIT
 */

namespace chillerlan\PrototypeDOM\Node;

use chillerlan\PrototypeDOM\NodeList;

/**
 * @property string                                $nodeName
 * @property string                                $nodeValue
 * @property int                                   $nodeType
 * @property \chillerlan\PrototypeDOM\Node\Element $parentNode
 * @property \DOMNodeList                          $childNodes
 * @property \chillerlan\PrototypeDOM\Node\Element $firstChild
 * @property \chillerlan\PrototypeDOM\Node\Element $lastChild
 * @property \chillerlan\PrototypeDOM\Node\Element $previousSibling
 * @property \chillerlan\PrototypeDOM\Node\Element $nextSibling
 * @property \DOMNamedNodeMap                      $attributes
 * @property \chillerlan\PrototypeDOM\Document     $ownerDocument
 * @property string                                $namespaceURI
 * @property string                                $prefix
 * @property string                                $localName
 * @property string                                $baseURI
 * @property string                                $textContent
 */
interface PrototypeNode{

	/**
	 * @link http://api.prototypejs.org/dom/Element/recursivelyCollect/
	 *
	 * @param string $property
	 * @param int    $maxLength
	 * @param int    $nodeType https://secure.php.net/manual/dom.constants.php
	 *
	 * @return \chillerlan\PrototypeDOM\NodeList
	 */
	public function recursivelyCollect(string $property, int $maxLength = null, int $nodeType = null):NodeList;

	/**
	 * @link http://api.prototypejs.org/dom/Element/empty/
	 *
	 * @return bool
	 */
	public function empty():bool;

	/**
	 * @link http://api.prototypejs.org/dom/Element/inspect/
	 *
	 * @param bool $xml
	 *
	 * @return string
	 */
	public function inspect(bool $xml = null):string;

	/**
	 * @link http://api.prototypejs.org/dom/Element/remove/
	 *
	 * @return \chillerlan\PrototypeDOM\Node\PrototypeNode
	 */
	public function remove():PrototypeNode;

	/**
	 * @link http://api.prototypejs.org/dom/Element/replace/
	 *
	 * @param \chillerlan\PrototypeDOM\Node\PrototypeNode $newnode
	 *
	 * @return \chillerlan\PrototypeDOM\Node\PrototypeNode
	 */
	public function replace(PrototypeNode $newnode):PrototypeNode;

	/**
	 * @link http://api.prototypejs.org/dom/Element/cleanWhitespace/
	 *
	 * @return \chillerlan\PrototypeDOM\Node\PrototypeNode
	 */
	public function cleanWhitespace():PrototypeNode;

}
