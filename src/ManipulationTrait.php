<?php
/**
 * Trait ManipulationTrait
 *
 * @filesource   ManipulationTrait.php
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
trait ManipulationTrait{

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

		if(is_array($content)){

			foreach(['before', 'after', 'top', 'bottom'] as $pos){

				if(array_key_exists($pos, $content)){
					$nodes = $this->ownerDocument->_toNodeList($content[$pos]);

					if($pos === 'top'){
						$nodes->reverse();
					}

					foreach($nodes as $node){
						call_user_func_array([$this, 'insert_'.$pos], [$node]);
					}

				}

			}

		}
		else{
			foreach($this->ownerDocument->_toNodeList($content) as $node){
				$this->insert_bottom($node);
			}
		}

		/** @var \chillerlan\PrototypeDOM\Element $this */
		return $this;
	}

	/**
	 * @param \DOMNode      $node
	 * @param \DOMNode|null $refNode
	 *
	 * @return \DOMNode
	 */
	public function insert_before(DOMNode $node, DOMNode $refNode = null):DOMNode{

		if($this->parentNode){
			$this->parentNode->insertBefore($this->_importNode($node), $refNode ?? $this);
		}

		/** @var \chillerlan\PrototypeDOM\Element $this */
		return $this;
	}

	/**
	 * @param \DOMNode $node
	 *
	 * @return \DOMNode
	 */
	public function insert_after(DOMNode $node):DOMNode{
		!$this->nextSibling && $this->parentNode
			? $this->parentNode->insert_bottom($node)
			: $this->nextSibling->insert_before($node);

		/** @var \chillerlan\PrototypeDOM\Element $this */
		return $this;
	}

	/**
	 * @param \DOMNode $node
	 *
	 * @return \DOMNode
	 */
	public function insert_top(DOMNode $node):DOMNode{
		$this->hasChildNodes()
			? $this->firstChild->insert_before($node, $this->firstChild)
			: $this->insert_bottom($node);

		/** @var \chillerlan\PrototypeDOM\Element $this */
		return $this;
	}

	/**
	 * @param \DOMNode $node
	 *
	 * @return \DOMNode
	 */
	public function insert_bottom(DOMNode $node):DOMNode{
		$this->appendChild($this->_importNode($node));

		/** @var \chillerlan\PrototypeDOM\Element $this */
		return $this;
	}

}
