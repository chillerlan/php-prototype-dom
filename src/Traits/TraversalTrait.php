<?php
/**
 * Trait TraversalTrait
 *
 * @filesource   TraversalTrait.php
 * @created      06.05.2017
 * @package      chillerlan\PrototypeDOM\Traits
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2017 Smiley
 * @license      MIT
 */

namespace chillerlan\PrototypeDOM\Traits;

use chillerlan\PrototypeDOM\Node\PrototypeNode;
use chillerlan\PrototypeDOM\NodeList;

trait TraversalTrait{
	use  NodeTrait;

	/**
	 * @param        $selector
	 * @param        $index
	 * @param string $property
	 * @param int    $nodeType
	 *
	 * @return \chillerlan\PrototypeDOM\Node\PrototypeNode|null
	 */
	public function _recursivelyFind($selector, $index, string $property, int $nodeType = XML_ELEMENT_NODE){

		if(is_numeric($selector)){
			$index    = $selector;
			$selector = null;
		}

		return $this->ownerDocument->_recursivelyFind($this, $property, $selector, $index ?? 0, $nodeType);
	}

	/**
	 * @link http://api.prototypejs.org/dom/Element/select/
	 *
	 * @param string|array $selectors
	 *
	 * @return \chillerlan\PrototypeDOM\NodeList
	 */
	public function select($selectors = null):NodeList{
		return $this->ownerDocument->select($selectors, $this, 'descendant::');
	}

	/**
	 * @link http://api.prototypejs.org/dom/Element/match/
	 *
	 * @param string $selector
	 *
	 * @return bool
	 */
	public function match(string $selector):bool{
		return $this->ownerDocument->match($this, $selector);
	}

	/**
	 * @link http://api.prototypejs.org/dom/Element/down/
	 *
	 * @param null $expression
	 * @param int  $index
	 *
	 * @return \chillerlan\PrototypeDOM\Node\PrototypeNode|null
	 */
	public function down($expression = null, int $index = null){

		if(count(func_get_args()) === 0){
			return $this->firstDescendant();
		}

		if(is_int($expression)){
			$index      = $expression;
			$expression = '*';
		}
		else{
			$index = $index ?? 0;
		}

		return $this->select($expression ?? null)->item($index);
	}

	/**
	 * @link http://api.prototypejs.org/dom/Element/up/
	 *
	 * @param string|null $expression
	 * @param int|null    $index
	 *
	 * @return \chillerlan\PrototypeDOM\Node\PrototypeNode|null
	 */
	public function up($expression = null, int $index = null){
		return $this->_recursivelyFind($expression, $index, 'parentNode');
	}

	/**
	 * @link http://api.prototypejs.org/dom/Element/previous/
	 *
	 * @param string|null $expression
	 * @param int|null    $index
	 *
	 * @return \chillerlan\PrototypeDOM\Node\PrototypeNode|null
	 */
	public function previous($expression = null, int $index = null){
		return $this->_recursivelyFind($expression, $index, 'previousSibling');
	}

	/**
	 * @link http://api.prototypejs.org/dom/Element/next/
	 *
	 * @param string|null $expression
	 * @param int|null    $index
	 *
	 * @return \chillerlan\PrototypeDOM\Node\PrototypeNode|null
	 */
	public function next($expression = null, int $index = null){
		return $this->_recursivelyFind($expression, $index, 'nextSibling');
	}

	/**
	 * @link http://api.prototypejs.org/dom/Element/childElements/
	 *
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
	 * @link http://api.prototypejs.org/dom/Element/descendantOf/
	 *
	 * @param \chillerlan\PrototypeDOM\Node\PrototypeNode $ancestor
	 *
	 * @return bool
	 */
	public function descendantOf(PrototypeNode $ancestor):bool{
		return $this->ancestors()->match($ancestor);
	}

	/**
	 * @link http://api.prototypejs.org/dom/Element/ancestors/
	 *
	 * @return \chillerlan\PrototypeDOM\NodeList
	 */
	public function ancestors():NodeList{
		return $this->recursivelyCollect('parentNode');
	}

	/**
	 * @link http://api.prototypejs.org/dom/Element/siblings/
	 *
	 * @return \chillerlan\PrototypeDOM\NodeList
	 */
	public function siblings():NodeList{
		return $this->previousSiblings()->reverse()->merge($this->nextSiblings());
	}

	/**
	 * @link http://api.prototypejs.org/dom/Element/descendants/
	 *
	 * @return \chillerlan\PrototypeDOM\NodeList
	 */
	public function descendants():NodeList{
		return $this->select();
	}

	/**
	 * @link http://api.prototypejs.org/dom/Element/firstDescendant/
	 *
	 * @return \chillerlan\PrototypeDOM\Node\Element|null
	 */
	public function firstDescendant(){
		return $this->descendants()->first();
	}

	/**
	 * @link http://api.prototypejs.org/dom/Element/previousSiblings/
	 *
	 * @return \chillerlan\PrototypeDOM\NodeList
	 */
	public function previousSiblings():NodeList{
		return $this->recursivelyCollect('previousSibling');
	}

	/**
	 * @link http://api.prototypejs.org/dom/Element/nextSiblings/
	 *
	 * @return \chillerlan\PrototypeDOM\NodeList
	 */
	public function nextSiblings():NodeList{
		return $this->recursivelyCollect('nextSibling');
	}

}
