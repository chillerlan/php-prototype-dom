<?php
/**
 * Trait NodeManipulationTrait
 *
 * @filesource   NodeManipulationTrait.php
 * @created      08.05.2017
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
trait NodeManipulationTrait{

	/**
	 * @link http://php.net/manual/class.domnode.php#domnode.props.ownerdocument
	 *
	 * @var \chillerlan\PrototypeDOM\Document
	 */
	public $ownerDocument;

	/**
	 * @var \chillerlan\PrototypeDOM\Element
	 */
	public $parentNode;

	/**
	 * @var \chillerlan\PrototypeDOM\Element
	 */
	public $firstChild;

	/**
	 * @var \chillerlan\PrototypeDOM\Element
	 */
	public $nextSibling;

	/**
	 * @return \DOMNode
	 */
	public function remove():DOMNode{

		if(!$this->parentNode){
			/** @var \chillerlan\PrototypeDOM\Element $this */
			return $this;
		}

		/** @var \chillerlan\PrototypeDOM\Element $this */
		return $this->parentNode->removeChild($this);
	}

	/**
	 * @param \DOMNode $newnode
	 *
	 * @return \DOMNode
	 */
	public function replace(DOMNode $newnode):DOMNode{

		if(!$this->parentNode){
			/** @var \chillerlan\PrototypeDOM\Element $this */
			return $this;
		}

		/** @var \chillerlan\PrototypeDOM\Element $this */
		return $this->parentNode->replaceChild($this->_importNode($newnode), $this);
	}

	/**
	 * @param \DOMNode $wrapper
	 *
	 * @return \DOMNode
	 */
	public function wrap(DOMNode $wrapper):DOMNode{
		/** @var \chillerlan\PrototypeDOM\Element $wrapper */
		return $wrapper->insert($this->replace($wrapper));
	}

	/**
	 * @return \DOMNode
	 */
	public function empty():DOMNode{

		while($this->hasChildNodes()){
			$this->firstChild->remove();
		}

		/** @var \chillerlan\PrototypeDOM\Element $this */
		return $this;
	}

	/**
	 * @param string|\DOMNode|\DOMNodeList $content
	 *
	 * @return \DOMNode
	 */
	public function update($content):DOMNode{
		$this->empty();
		$this->insert($content);

		/** @var \chillerlan\PrototypeDOM\Element $this */
		return $this;
	}

	/**
	 * @return \DOMNode
	 */
	public function cleanWhitespace():DOMNode{
		$node = $this->firstChild;

		while($node){
			$nextNode = $node->nextSibling;

			if($node->nodeType === XML_TEXT_NODE && empty(trim($node->nodeValue))){
				$node->remove();
			}

			$node = $nextNode;
		}

		/** @var \chillerlan\PrototypeDOM\Element $this */
		return $this;
	}

	/**
	 * @param \DOMNode $newNode
	 *
	 * @return \DOMNode
	 */
	public function _importNode(DOMNode $newNode):DOMNode{
		return $this->ownerDocument->importNode($newNode, true);
	}

	/**
	 * @param \DOMNodeList $nodes
	 *
	 * @return \DOMNode
	 */
	public function _insertBottom(DOMNodeList $nodes):DOMNode{

		foreach($nodes as $node){
			$this->appendChild($this->_importNode($node));
		}

		/** @var \chillerlan\PrototypeDOM\Element $this */
		return $this;
	}

	/**
	 * @param \DOMNodeList $nodes
	 *
	 * @return \DOMNode
	 */
	public function _insertBefore(DOMNodeList $nodes){

		if($this->parentNode){

			foreach($nodes as $node){
				$this->parentNode->insertBefore($this->_importNode($node), $this);
			}

		}

		/** @var \chillerlan\PrototypeDOM\Element $this */
		return $this;
	}

	/**
	 * @param \DOMNodeList $nodes
	 *
	 * @return \DOMNode
	 */
	public function _insertTop(DOMNodeList $nodes):DOMNode{
		return $this->hasChildNodes()
			? $this->firstChild->_insertBefore($nodes)
			: $this->_insertBottom($nodes);
	}

	/**
	 * @param \DOMNodeList $nodes
	 *
	 * @return \DOMNode
	 */
	public function _insertAfter(DOMNodeList $nodes):DOMNode{
		return !$this->nextSibling && $this->parentNode
			? $this->parentNode->_insertBottom($nodes)
			: $this->nextSibling->_insertBefore($nodes);
	}

	/**
	 * Accepted insertion points are:
	 * - before (as element's previous sibling)
	 * - after (as element's next sibling)
	 * - top (as element's first child)
	 * - bottom (as element's last child)
	 *
	 * @param string|array|\DOMNode|\DOMNodeList $content
	 *
	 * @return \DOMNode
	 */
	public function insert($content):DOMNode{

		if(is_string($content) || $content instanceof DOMNode || $content instanceof DOMNodeList){
			$this->_insertBottom($this->ownerDocument->_toDOMNodeList($content));
		}
		elseif(is_array($content)){

			foreach(['before', 'after', 'top', 'bottom'] as $pos){

				if(array_key_exists($pos, $content)){
					call_user_func_array(
						[$this, '_insert'.ucfirst($pos)],
						[$this->ownerDocument->_toDOMNodeList($content[$pos])]
					);
				}

			}

		}

		/** @var \chillerlan\PrototypeDOM\Element $this */
		return $this;
	}

}
