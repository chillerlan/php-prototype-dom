<?php
/**
 * Trait TraversalTrait
 *
 * @filesource   TraversalTrait.php
 * @created      06.05.2017
 * @package      chillerlan\PrototypeDOM
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2017 Smiley
 * @license      MIT
 */

namespace chillerlan\PrototypeDOM;

use DOMNode;

/**
 * @extends \DOMNode
 */
trait TraversalTrait{

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
	 * @return \chillerlan\PrototypeDOM\NodeList
	 */
	public function recursivelyCollect(string $property, int $maxLength = -1, int $nodeType = XML_ELEMENT_NODE):NodeList{
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
	 * @return \chillerlan\PrototypeDOM\NodeList
	 */
	public function select($selectors = null):NodeList{
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
	 * @return \chillerlan\PrototypeDOM\NodeList
	 */
	public function childElements(int $nodeType = XML_ELEMENT_NODE):NodeList{
		$children = new NodeList;

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
		return $this->ancestors()->match($ancestor);
	}

	/**
	 * @return \chillerlan\PrototypeDOM\NodeList
	 */
	public function ancestors():NodeList{
		return $this->recursivelyCollect('parentNode');
	}

	/**
	 * @return \chillerlan\PrototypeDOM\NodeList
	 */
	public function siblings():NodeList{
		return $this->previousSiblings()->reverse()->merge($this->nextSiblings());
	}

	/**
	 * @return \chillerlan\PrototypeDOM\NodeList
	 */
	public function descendants():NodeList{
		return $this->select();
	}

	/**
	 * @return \DOMNode|null
	 */
	public function firstDescendant(){
		return $this->descendants()[0] ?? null;
	}

	/**
	 * @return \chillerlan\PrototypeDOM\NodeList
	 */
	public function previousSiblings():NodeList{
		return $this->recursivelyCollect('previousSibling');
	}

	/**
	 * @return \chillerlan\PrototypeDOM\NodeList
	 */
	public function nextSiblings():NodeList{
		return $this->recursivelyCollect('nextSibling');
	}

}
