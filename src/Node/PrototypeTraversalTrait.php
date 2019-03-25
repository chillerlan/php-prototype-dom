<?php
/**
 * Trait PrototypeTraversalTrait
 *
 * @filesource   PrototypeTraversalTrait.php
 * @created      06.05.2017
 * @package      chillerlan\PrototypeDOM\Traits
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2017 Smiley
 * @license      MIT
 */

namespace chillerlan\PrototypeDOM\Node;

use chillerlan\PrototypeDOM\NodeList;
use DOMNode;

/**
 * @property \chillerlan\PrototypeDOM\Document $ownerDocument
 */
trait PrototypeTraversalTrait{
	use PrototypeNodeTrait;

	/**
	 * @param        $selector
	 * @param        $index
	 * @param string $property
	 * @param int    $nodeType https://secure.php.net/manual/dom.constants.php
	 *
	 * @return \chillerlan\PrototypeDOM\Node\PrototypeTraversal|\DOMNode|null
	 */
	public function recursivelyFind($selector, int $index = null, string $property = null, int $nodeType = \XML_ELEMENT_NODE):?PrototypeTraversal{

		if(\is_numeric($selector)){
			return $this->ownerDocument->recursivelyFind($this, $property, null, $selector, $nodeType);
		}

		return $this->ownerDocument->recursivelyFind($this, $property, $selector, $index ?? 0, $nodeType);
	}

	/**
	 * @link http://api.prototypejs.org/dom/Element/select/
	 *
	 * @param array $selectors
	 *
	 * @return \chillerlan\PrototypeDOM\NodeList
	 */
	public function select(array $selectors = null):NodeList{
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
	 * @return \chillerlan\PrototypeDOM\Node\PrototypeTraversal|null
	 */
	public function down($expression = null, int $index = null):?PrototypeTraversal{

		if($expression === null && $index === null){
			return $this->firstDescendant();
		}

		$index = $index ?? 0;

		if(\is_int($expression)){
			return $this->select(['*'])->item($expression);
		}

		if(\is_array($expression)){
			return $this->select($expression)->item($index);
		}

		return $this->select([$expression])->item($index);
	}

	/**
	 * @link http://api.prototypejs.org/dom/Element/up/
	 *
	 * @param string|null $expression
	 * @param int|null    $index
	 *
	 * @return \chillerlan\PrototypeDOM\Node\PrototypeTraversal|null
	 */
	public function up($expression = null, int $index = null):?PrototypeTraversal{
		return $this->recursivelyFind($expression, $index, 'parentNode');
	}

	/**
	 * @link http://api.prototypejs.org/dom/Element/previous/
	 *
	 * @param string|null $expression
	 * @param int|null    $index
	 *
	 * @return \chillerlan\PrototypeDOM\Node\PrototypeTraversal|null
	 */
	public function previous($expression = null, int $index = null):?PrototypeTraversal{
		return $this->recursivelyFind($expression, $index, 'previousSibling');
	}

	/**
	 * @link http://api.prototypejs.org/dom/Element/next/
	 *
	 * @param string|null $expression
	 * @param int|null    $index
	 *
	 * @return \chillerlan\PrototypeDOM\Node\PrototypeTraversal|null
	 */
	public function next($expression = null, int $index = null):?PrototypeTraversal{
		return $this->recursivelyFind($expression, $index, 'nextSibling');
	}

	/**
	 * @link http://api.prototypejs.org/dom/Element/childElements/
	 *
	 * @param int $nodeType https://secure.php.net/manual/dom.constants.php
	 *
	 * @return \chillerlan\PrototypeDOM\NodeList
	 */
	public function childElements(int $nodeType = \XML_ELEMENT_NODE):NodeList{
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
	 * @link http://api.prototypejs.org/dom/Element/descendantOf/
	 *
	 * @param \DOMNode $ancestor
	 *
	 * @return bool
	 */
	public function descendantOf(DOMNode $ancestor):bool{
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
	 * @return \chillerlan\PrototypeDOM\Node\PrototypeTraversal|null
	 */
	public function firstDescendant():?PrototypeTraversal{
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
