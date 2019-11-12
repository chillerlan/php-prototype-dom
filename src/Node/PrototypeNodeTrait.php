<?php
/**
 * Trait PrototypeNodeTrait
 *
 * @filesource   PrototypeNodeTrait.php
 * @created      11.05.2017
 * @package      chillerlan\PrototypeDOM\Node
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2017 Smiley
 * @license      MIT
 */

namespace chillerlan\PrototypeDOM\Node;

use chillerlan\PrototypeDOM\NodeList;

use function trim;

use const XML_ELEMENT_NODE, XML_TEXT_NODE;

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
trait PrototypeNodeTrait{

	/**
	 * @link http://api.prototypejs.org/dom/Element/recursivelyCollect/
	 *
	 * @param string $property
	 * @param int    $maxLength
	 * @param int    $nodeType https://secure.php.net/manual/dom.constants.php
	 *
	 * @return \chillerlan\PrototypeDOM\NodeList
	 */
	public function recursivelyCollect(string $property, int $maxLength = null, int $nodeType = null):NodeList{
		return $this->ownerDocument->recursivelyCollect($this, $property, $maxLength ?? -1, $nodeType ?? XML_ELEMENT_NODE);
	}

	/**
	 * @link http://api.prototypejs.org/dom/Element/empty/
	 *
	 * @return bool
	 */
	public function empty():bool{
		return empty(trim($this->nodeValue));
	}

	/**
	 * @link http://api.prototypejs.org/dom/Element/inspect/
	 *
	 * @param bool $xml
	 *
	 * @return string
	 */
	public function inspect(bool $xml = null):string{
		return $this->ownerDocument->inspect($this, $xml);
	}

	/**
	 * @link http://api.prototypejs.org/dom/Element/remove/
	 *
	 * @return \chillerlan\PrototypeDOM\Node\PrototypeNode
	 */
	public function remove():PrototypeNode{

		if(!$this->parentNode){
			return $this;
		}

		return $this->parentNode->removeChild($this);
	}

	/**
	 * @link http://api.prototypejs.org/dom/Element/replace/
	 *
	 * @param \chillerlan\PrototypeDOM\Node\PrototypeNode $newnode
	 *
	 * @return \chillerlan\PrototypeDOM\Node\PrototypeNode
	 */
	public function replace(PrototypeNode $newnode):PrototypeNode{

		if(!$this->parentNode){
			return $this;
		}

		return $this->parentNode->replaceChild($this->importNode($newnode), $this);
	}

	/**
	 * @return \chillerlan\PrototypeDOM\Node\PrototypeNode
	 */
	public function purge():PrototypeNode{

		while($this->hasChildNodes()){
			$this->firstChild->remove();
		}

		return $this;
	}

	/**
	 * @link http://api.prototypejs.org/dom/Element/cleanWhitespace/
	 *
	 * @return \chillerlan\PrototypeDOM\Node\PrototypeNode
	 */
	public function cleanWhitespace():PrototypeNode{
		$node = $this->firstChild;

		while($node){
			$nextNode = $node->nextSibling;

			if($node->nodeType === XML_TEXT_NODE && $node->empty()){
				$node->remove();
			}

			$node = $nextNode;
		}

		return $this;
	}

	/**
	 * @param \chillerlan\PrototypeDOM\Node\PrototypeNode $newNode
	 *
	 * @return \chillerlan\PrototypeDOM\Node\PrototypeNode
	 */
	public function importNode(PrototypeNode $newNode):PrototypeNode{
		return $this->ownerDocument->importNode($newNode, true);
	}

}
