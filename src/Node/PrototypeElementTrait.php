<?php
/**
 * Trait PrototypeElementTrait
 *
 * @created      08.05.2017
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2017 Smiley
 * @license      MIT
 *
 * @noinspection PhpParamsInspection
 * @noinspection PhpIncompatibleReturnTypeInspection
 */

namespace chillerlan\PrototypeDOM\Node;

use function array_key_exists, call_user_func_array, is_array;

/**
 * @extends    \DOMElement
 * @implements \chillerlan\PrototypeDOM\Node\PrototypeElement
 */
trait PrototypeElementTrait{
	use PrototypeTraversalTrait;

	/**
	 * @inheritDoc
	 */
	public function wrap(PrototypeElement $wrapper):PrototypeElement{
		return $wrapper->insert($this->replace($wrapper));
	}

	/**
	 * @inheritDoc
	 */
	public function update($content):PrototypeElement{
		return $this->purge()->insert($content);
	}

	/**
	 * @inheritDoc
	 */
	public function insert($content):PrototypeElement{

		if(!is_array($content)){

			foreach($this->ownerDocument->toNodeList($content) as $node){
				$this->insert_bottom($node);
			}

			return $this;
		}

		foreach(['before', 'after', 'top', 'bottom'] as $pos){

			if(!array_key_exists($pos, $content)){
				continue;
			}

			$nodes = $this->ownerDocument->toNodeList($content[$pos]);

			if($pos === 'top' && $this->hasChildNodes() || $pos === 'after' && $this->nextSibling){
				$nodes->reverse();
			}

			foreach($nodes as $node){
				call_user_func_array([$this, 'insert_'.$pos], [$node]);
			}

		}

		return $this;
	}

	/**
	 * @inheritDoc
	 */
	public function insert_before(PrototypeElement $node, PrototypeElement $refNode = null):PrototypeElement{

		if($this->parentNode){
			$this->parentNode->insertBefore($this->importNode($node), $refNode ?? $this);
		}

		return $this;
	}

	/**
	 * @inheritDoc
	 */
	public function insert_after(PrototypeElement $node):PrototypeElement{

		if(!$this->nextSibling && $this->parentNode){
			return $this->parentNode->insert_bottom($node); // @codeCoverageIgnore
		}

		return $this->nextSibling->insert_before($node);
	}

	/**
	 * @inheritDoc
	 */
	public function insert_top(PrototypeElement $node):PrototypeElement{

		if($this->hasChildNodes()){
			return $this->firstChild->insert_before($node, $this->firstChild);
		}

		return $this->insert_bottom($node);
	}

	/**
	 * @inheritDoc
	 */
	public function insert_bottom(PrototypeElement $node):PrototypeElement{
		$this->appendChild($this->importNode($node));

		return $this;
	}

	/**
	 * @inheritDoc
	 */
	public function tag():?string{
		return $this->tagName ?? null;
	}
}
