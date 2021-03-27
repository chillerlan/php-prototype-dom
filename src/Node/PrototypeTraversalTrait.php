<?php
/**
 * Trait PrototypeTraversalTrait
 *
 * @created      06.05.2017
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2017 Smiley
 * @license      MIT
 *
 * @noinspection PhpParamsInspection
 * @noinspection PhpIncompatibleReturnTypeInspection
 */

namespace chillerlan\PrototypeDOM\Node;

use chillerlan\PrototypeDOM\NodeList;
use DOMNode;

use function is_array, is_int, is_numeric;

use const XML_ELEMENT_NODE;

/**
 * @extends    \DOMNode
 * @implements \chillerlan\PrototypeDOM\Node\PrototypeTraversal
 */
trait PrototypeTraversalTrait{
	use PrototypeNodeTrait;

	/**
	 * @inheritDoc
	 */
	public function recursivelyFind(
		string $selector = null,
		int $index = null,
		string $property = null,
		int $nodeType = XML_ELEMENT_NODE
	):?PrototypeTraversal{

		if(is_numeric($selector)){
			return $this->ownerDocument->recursivelyFind($this, $property, null, $selector, $nodeType);
		}

		return $this->ownerDocument->recursivelyFind($this, $property, $selector, $index ?? 0, $nodeType);
	}

	/**
	 * @inheritDoc
	 */
	public function select(array $selectors = null):NodeList{
		return $this->ownerDocument->select($selectors, $this, 'descendant::');
	}

	/**
	 * @inheritDoc
	 */
	public function down($expression = null, int $index = null):?PrototypeTraversal{

		if($expression === null && $index === null){
			return $this->firstDescendant();
		}

		$index = $index ?? 0;

		if(is_int($expression)){
			return $this->select(['*'])->item($expression);
		}

		if(is_array($expression)){
			return $this->select($expression)->item($index);
		}

		return $this->select([$expression])->item($index);
	}

	/**
	 * @inheritDoc
	 */
	public function up($expression = null, int $index = null):?PrototypeTraversal{
		return $this->recursivelyFind($expression, $index, 'parentNode');
	}

	/**
	 * @inheritDoc
	 */
	public function previous($expression = null, int $index = null):?PrototypeTraversal{
		return $this->recursivelyFind($expression, $index, 'previousSibling');
	}

	/**
	 * @inheritDoc
	 */
	public function next($expression = null, int $index = null):?PrototypeTraversal{
		return $this->recursivelyFind($expression, $index, 'nextSibling');
	}

	/**
	 * @inheritDoc
	 */
	public function childElements(int $nodeType = null):NodeList{
		$nodeType = $nodeType ?? XML_ELEMENT_NODE;
		$children = new NodeList;

		if(!$this->hasChildNodes()){
			return $children;
		}

		foreach($this->childNodes as $child){

			if($child->nodeType === $nodeType){
				$children[] = $child;
			}

		}

		return $children;
	}

	/**
	 * @inheritDoc
	 */
	public function descendantOf(DOMNode $ancestor):bool{
		return $this->ancestors()->match($ancestor);
	}

	/**
	 * @inheritDoc
	 */
	public function ancestors():NodeList{
		return $this->recursivelyCollect('parentNode');
	}

	/**
	 * @inheritDoc
	 */
	public function siblings():NodeList{
		return $this->previousSiblings()->reverse()->merge($this->nextSiblings());
	}

	/**
	 * @inheritDoc
	 */
	public function descendants():NodeList{
		return $this->select();
	}

	/**
	 * @inheritDoc
	 */
	public function firstDescendant():?PrototypeTraversal{
		return $this->descendants()->first();
	}

	/**
	 * @inheritDoc
	 */
	public function previousSiblings():NodeList{
		return $this->recursivelyCollect('previousSibling');
	}

	/**
	 * @inheritDoc
	 */
	public function nextSiblings():NodeList{
		return $this->recursivelyCollect('nextSibling');
	}

}
