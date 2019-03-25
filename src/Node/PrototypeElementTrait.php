<?php
/**
 * Trait PrototypeElementTrait
 *
 * @filesource   PrototypeElementTrait.php
 * @created      08.05.2017
 * @package      chillerlan\PrototypeDOM\Node
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2017 Smiley
 * @license      MIT
 */

namespace chillerlan\PrototypeDOM\Node;

trait PrototypeElementTrait{
	use PrototypeTraversalTrait;

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
		return $this->purge()->insert($content);
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

		if(!\is_array($content)){

			foreach($this->ownerDocument->toNodeList($content) as $node){
				$this->insert_bottom($node);
			}

			return $this;
		}

		foreach(['before', 'after', 'top', 'bottom'] as $pos){

			if(!\array_key_exists($pos, $content)){
				continue;
			}

			$nodes = $this->ownerDocument->toNodeList($content[$pos]);

			if($pos === 'top' && $this->hasChildNodes() || $pos === 'after' && $this->nextSibling){
				$nodes->reverse();
			}

			foreach($nodes as $node){
				\call_user_func_array([$this, 'insert_'.$pos], [$node]);
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
			$this->parentNode->insertBefore($this->importNode($node), $refNode ?? $this);
		}

		return $this;
	}

	/**
	 * @param \chillerlan\PrototypeDOM\Node\PrototypeElement $node
	 *
	 * @return \chillerlan\PrototypeDOM\Node\PrototypeElement
	 */
	public function insert_after(PrototypeElement $node):PrototypeElement{

		if(!$this->nextSibling && $this->parentNode){
			return $this->parentNode->insert_bottom($node); // @codeCoverageIgnore
		}

		return $this->nextSibling->insert_before($node);
	}

	/**
	 * @param \chillerlan\PrototypeDOM\Node\PrototypeElement $node
	 *
	 * @return \chillerlan\PrototypeDOM\Node\PrototypeElement
	 */
	public function insert_top(PrototypeElement $node):PrototypeElement{

		if($this->hasChildNodes()){
			return $this->firstChild->insert_before($node, $this->firstChild);
		}

		return $this->insert_bottom($node);
	}

	/**
	 * @param \chillerlan\PrototypeDOM\Node\PrototypeElement $node
	 *
	 * @return \chillerlan\PrototypeDOM\Node\PrototypeElement
	 */
	public function insert_bottom(PrototypeElement $node):PrototypeElement{
		$this->appendChild($this->importNode($node));

		return $this;
	}

}
