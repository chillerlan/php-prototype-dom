<?php
/**
 * Trait NodeTrait
 *
 * @filesource   NodeTrait.php
 * @created      11.05.2017
 * @package      chillerlan\PrototypeDOM\Traits
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2017 Smiley
 * @license      MIT
 */

namespace chillerlan\PrototypeDOM\Traits;

use chillerlan\PrototypeDOM\Node\PrototypeNode;
use chillerlan\PrototypeDOM\NodeList;

/**
 * @property string $nodeName
 * @property string $nodeValue
 * @property int $nodeType
 * @property \chillerlan\PrototypeDOM\Node\Element $parentNode
 * @property \DOMNodeList $childNodes
 * @property \chillerlan\PrototypeDOM\Node\Element $firstChild
 * @property \chillerlan\PrototypeDOM\Node\Element $lastChild
 * @property \chillerlan\PrototypeDOM\Node\Element $previousSibling
 * @property \chillerlan\PrototypeDOM\Node\Element $nextSibling
 * @property \DOMNamedNodeMap $attributes
 * @property \chillerlan\PrototypeDOM\Document $ownerDocument
 * @property string $namespaceURI
 * @property string $prefix
 * @property string $localName
 * @property string $baseURI
 * @property string $textContent
 */
trait NodeTrait{

	/**
	 * @link http://api.prototypejs.org/dom/Element/recursivelyCollect/
	 *
	 * @param string $property
	 * @param int    $maxLength
	 * @param int    $nodeType
	 *
	 * @return \chillerlan\PrototypeDOM\NodeList
	 */
	public function recursivelyCollect(string $property, int $maxLength = -1, int $nodeType = XML_ELEMENT_NODE):NodeList{
		return $this->ownerDocument->recursivelyCollect($this, $property, $maxLength, $nodeType);
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
	 *
	 * @todo: fixme!
	 *
	 * @param bool $xml
	 *
	 * @return string
	 */
	public function inspect($xml = false):string{
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

		return $this->parentNode->replaceChild($this->_importNode($newnode), $this);
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
	public function _importNode(PrototypeNode $newNode):PrototypeNode{
		return $this->ownerDocument->importNode($newNode, true);
	}

}
