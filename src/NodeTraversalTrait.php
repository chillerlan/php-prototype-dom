<?php
/**
 * Trait NodeTraversalTrait
 *
 * @filesource   NodeTraversalTrait.php
 * @created      06.05.2017
 * @package      chillerlan\PrototypeDOM
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2017 Smiley
 * @license      MIT
 */

namespace chillerlan\PrototypeDOM;

use DOMNode, DOMNodeList;

/**
 * @extends \DOMNode
 */
trait NodeTraversalTrait{

	/**
	 * @link http://php.net/manual/class.domnode.php#domnode.props.ownerdocument
	 *
	 * @var \chillerlan\PrototypeDOM\Document
	 */
	public $ownerDocument;

	/**
	 * @param string $property
	 * @param int    $maxLength
	 * @param int    $nodeType
	 *
	 * @return array [\chillerlan\PrototypeDOM\Element]
	 */
	public function recursivelyCollect(string $property, int $maxLength = -1, int $nodeType = XML_ELEMENT_NODE):array{
		/** @var \DOMNode $this */
		return $this->ownerDocument->recursivelyCollect($this, $property, $maxLength, $nodeType);
	}

	/**
	 * @param        $selector
	 * @param        $index
	 * @param string $property
	 * @param int    $nodeType
	 *
	 * @return \DOMNode|null
	 */
	public function _recursivelyFind($selector, $index, string $property, int $nodeType = XML_ELEMENT_NODE){

		if(is_numeric($selector)){
			$index    = $selector;
			$selector = null;
		}

		/** @var \chillerlan\PrototypeDOM\Element $this */
		return $this->ownerDocument->_recursivelyFind($this, $property, $selector, $index ?? 0, $nodeType);
	}

	/**
	 * @param bool $xml
	 *
	 * @return string
	 */
	public function inspect($xml = false):string{
		return $this->ownerDocument->inspect($this, $xml);
	}

	/**
	 * @param string|array $selectors
	 *
	 * @return array
	 */
	public function select($selectors = null):array{
		return $this->ownerDocument->select($selectors, $this, 'descendant::');
	}

	/**
	 * @param string $selector
	 *
	 * @return bool
	 */
	public function match(string $selector):bool{
		/** @var \chillerlan\PrototypeDOM\Element $this */
		return $this->ownerDocument->match($this, $selector);
	}

	/**
	 * @param null $expression
	 * @param int  $index
	 *
	 * @return \DOMNode|null
	 */
	public function down($expression = null, int $index = 0){

		if(count(func_get_args()) === 0){
			return $this->firstDescendant();
		}

		if(is_numeric($expression)){
			$index      = $expression;
			$expression = '*';
		}

		return $this->select(is_string($expression) ? $expression : null)[$index] ?? null;
	}

	/**
	 * @param string|null $expression
	 * @param int|null    $index
	 *
	 * @return \DOMNode|null
	 */
	public function up($expression = null, int $index = null){
		return $this->_recursivelyFind($expression, $index, 'parentNode');
	}

	/**
	 * @param string|null $expression
	 * @param int|null    $index
	 *
	 * @return \DOMNode|null
	 */
	public function previous($expression = null, int $index = null){
		return $this->_recursivelyFind($expression, $index, 'previousSibling');
	}

	/**
	 * @param string|null $expression
	 * @param int|null    $index
	 *
	 * @return \DOMNode|null
	 */
	public function next($expression = null, int $index = null){
		return $this->_recursivelyFind($expression, $index, 'nextSibling');
	}

	/**
	 * @param int $nodeType
	 *
	 * @return array[\chillerlan\PrototypeDOM\Element]
	 */
	public function childElements(int $nodeType = XML_ELEMENT_NODE):array{
		$children = [];

		if($this->hasChildNodes()){

			foreach($this->childNodes as $child){

				if($child->nodeType === $nodeType){
					$children[] = $child;
				}

			}

		}

		return $children;
	}

	/**
	 * @param \DOMNode $ancestor
	 *
	 * @return bool
	 */
	public function descendantOf(DOMNode $ancestor):bool{

		foreach($this->ancestors() as $match){

			if($ancestor->isSameNode($match)){
				return true;
			}

		}

		return false;
	}

	/**
	 * @return array[\chillerlan\PrototypeDOM\Element]
	 */
	public function ancestors():array{
		return $this->recursivelyCollect('parentNode');
	}

	/**
	 * @return array[\chillerlan\PrototypeDOM\Element]
	 */
	public function siblings():array{
		return array_merge(array_reverse($this->previousSiblings()), $this->nextSiblings());
	}

	/**
	 * @return array[\chillerlan\PrototypeDOM\Element]
	 */
	public function descendants():array{
		return $this->select();
	}

	/**
	 * @return \DOMNode|null
	 */
	public function firstDescendant(){
		return $this->descendants()[0] ?? null;
	}

	/**
	 * @return array[\chillerlan\PrototypeDOM\Element]
	 */
	public function previousSiblings():array{
		return $this->recursivelyCollect('previousSibling');
	}

	/**
	 * @return array[\chillerlan\PrototypeDOM\Element]
	 */
	public function nextSiblings():array{
		return $this->recursivelyCollect('nextSibling');
	}

}
