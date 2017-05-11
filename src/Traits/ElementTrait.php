<?php
/**
 * Trait ElementTrait
 *
 * @filesource   ElementTrait.php
 * @created      08.05.2017
 * @package      chillerlan\PrototypeDOM\Traits
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2017 Smiley
 * @license      MIT
 */

namespace chillerlan\PrototypeDOM\Traits;

use chillerlan\PrototypeDOM\Node\PrototypeElement;

trait ElementTrait{
	use TraversalTrait;

	/**
	 * @link http://api.prototypejs.org/dom/Element/wrap/
	 *
	 * @param \chillerlan\PrototypeDOM\Node\PrototypeElement $wrapper
	 *
	 * @return \chillerlan\PrototypeDOM\Node\PrototypeElement
	 */
	public function wrap(PrototypeElement $wrapper):PrototypeElement{
		return $wrapper->insert($this->replace($wrapper));
	}

	/**
	 * @link http://api.prototypejs.org/dom/Element/update/
	 *
	 * @param string|\DOMNode|\DOMNodeList $content
	 *
	 * @return \chillerlan\PrototypeDOM\Node\PrototypeElement
	 */
	public function update($content):PrototypeElement{
		$this->purge();
		$this->insert($content);

		return $this;
	}

	/**
	 * @link http://api.prototypejs.org/dom/Element/insert/
	 *
	 * Accepted insertion points are:
	 * - before (as element's previous sibling)
	 * - after (as element's next sibling)
	 * - top (as element's first child)
	 * - bottom (as element's last child)
	 *
	 * @param string|array|\DOMNode|\DOMNodeList $content
	 *
	 * @return \chillerlan\PrototypeDOM\Node\PrototypeElement
	 */
	public function insert($content):PrototypeElement{

		if(is_array($content)){

			foreach(['before', 'after', 'top', 'bottom'] as $pos){

				if(array_key_exists($pos, $content)){
					$nodes = $this->ownerDocument->_toNodeList($content[$pos]);

					if($pos === 'top' && $this->hasChildNodes() || $pos === 'after' && $this->nextSibling){
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

		return $this;
	}

	/**
	 * @param \chillerlan\PrototypeDOM\Node\PrototypeElement      $node
	 * @param \chillerlan\PrototypeDOM\Node\PrototypeElement|null $refNode
	 *
	 * @return \chillerlan\PrototypeDOM\Node\PrototypeElement
	 */
	public function insert_before(PrototypeElement $node, PrototypeElement $refNode = null):PrototypeElement{

		if($this->parentNode){
			$this->parentNode->insertBefore($this->_importNode($node), $refNode ?? $this);
		}

		return $this;
	}

	/**
	 * @param \chillerlan\PrototypeDOM\Node\PrototypeElement $node
	 *
	 * @return \chillerlan\PrototypeDOM\Node\PrototypeElement
	 */
	public function insert_after(PrototypeElement $node):PrototypeElement{
		return !$this->nextSibling && $this->parentNode
			? $this->parentNode->insert_bottom($node)
			: $this->nextSibling->insert_before($node);
	}

	/**
	 * @param \chillerlan\PrototypeDOM\Node\PrototypeElement $node
	 *
	 * @return \chillerlan\PrototypeDOM\Node\PrototypeElement
	 */
	public function insert_top(PrototypeElement $node):PrototypeElement{
		return $this->hasChildNodes()
			? $this->firstChild->insert_before($node, $this->firstChild)
			: $this->insert_bottom($node);
	}

	/**
	 * @param \chillerlan\PrototypeDOM\Node\PrototypeElement $node
	 *
	 * @return \chillerlan\PrototypeDOM\Node\PrototypeElement
	 */
	public function insert_bottom(PrototypeElement $node):PrototypeElement{
		$this->appendChild($this->_importNode($node));

		return $this;
	}

}
