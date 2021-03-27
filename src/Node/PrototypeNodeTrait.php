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
 *
 * @noinspection PhpParamsInspection
 * @noinspection PhpIncompatibleReturnTypeInspection
 */

namespace chillerlan\PrototypeDOM\Node;

use chillerlan\PrototypeDOM\NodeList;

use function trim;

use const XML_ELEMENT_NODE, XML_TEXT_NODE;

/**
 * @extends    \DOMNode
 * @implements \chillerlan\PrototypeDOM\Node\PrototypeNode
 */
trait PrototypeNodeTrait{

	/**
	 * @inheritDoc
	 */
	public function recursivelyCollect(string $property, int $maxLength = null, int $nodeType = null):NodeList{
		return $this->ownerDocument->recursivelyCollect($this, $property, $maxLength ?? -1, $nodeType ?? XML_ELEMENT_NODE);
	}

	/**
	 * @inheritDoc
	 */
	public function empty():bool{
		return empty(trim($this->nodeValue));
	}

	/**
	 * @inheritDoc
	 */
	public function inspect(bool $xml = null):string{
		return $this->ownerDocument->inspect($this, $xml);
	}

	/**
	 * @inheritDoc
	 */
	public function removeNode():PrototypeNode{

		if(!$this->parentNode){
			return $this;
		}

		return $this->parentNode->removeChild($this);
	}

	/**
	 * @inheritDoc
	 */
	public function replace(PrototypeNode $newNode):PrototypeNode{

		if(!$this->parentNode){
			return $this;
		}

		return $this->parentNode->replaceChild($this->importNode($newNode), $this);
	}

	/**
	 * @inheritDoc
	 */
	public function cleanWhitespace():PrototypeNode{
		$node = $this->firstChild;

		while($node){
			$nextNode = $node->nextSibling;

			if($node->nodeType === XML_TEXT_NODE && $node->empty()){
				$node->removeNode();
			}

			$node = $nextNode;
		}

		return $this;
	}

	/**
	 * @inheritDoc
	 */
	public function purge():PrototypeNode{

		while($this->hasChildNodes()){
			$this->firstChild->removeNode();
		}

		return $this;
	}

	/**
	 * @inheritDoc
	 */
	public function importNode(PrototypeNode $newNode):PrototypeNode{
		return $this->ownerDocument->importNode($newNode, true);
	}

	/**
	 * @inheritDoc
	 */
	public function match(string $selector):bool{
		return $this->ownerDocument->match($this, $selector);
	}

	/**
	 * @inheritDoc
	 */
	public function name():string{
		return $this->nodeName;
	}

	/**
	 * @inheritDoc
	 */
	public function value(bool $trimmed = true):string{
		return $trimmed ? trim($this->nodeValue) : $this->nodeValue;
	}

}
