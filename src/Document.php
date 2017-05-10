<?php
/**
 * Class Document
 *
 * @filesource   Document.php
 * @created      05.05.2017
 * @package      chillerlan\PrototypeDOM
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2017 Smiley
 * @license      MIT
 */

namespace chillerlan\PrototypeDOM;

use DOMDocument, DOMNode, DOMNodeList, DOMXPath;
use Symfony\Component\CssSelector\CssSelectorConverter;

class Document extends DOMDocument{

	/**
	 * Document constructor.
	 *
	 * @param string|null $version
	 * @param string|null $encoding
	 */
	public function __construct($version = null, $encoding = null){
		parent::__construct($version, $encoding);

		$this->registerNodeClass('DOMElement', Element::class);
		$this->registerNodeClass('DOMText', Text::class);
		$this->registerNodeClass('DOMCharacterData', CharacterData::class);
		$this->registerNodeClass('DOMDocumentFragment', DocumentFragment::class);
		$this->registerNodeClass('DOMDocumentType', DocumentType::class);
		$this->registerNodeClass('DOMComment', Comment::class);
		$this->registerNodeClass('DOMAttr', Attr::class);
	}

	/**
	 * @param string $selector
	 * @param string $axis
	 *
	 * @return string
	 */
	public function selector2xpath(string $selector, string $axis = '//'):string{
		return (new CssSelectorConverter)->toXPath($selector, $axis);
	}

	/**
	 * @param string        $xpath
	 * @param \DOMNode|null $contextNode
	 *
	 * @return \chillerlan\PrototypeDOM\NodeList
	 */
	public function query(string $xpath, DOMNode $contextNode = null):NodeList{
		return new NodeList((new DOMXPath($this))->query($xpath, $contextNode));
	}

	/**
	 * @param string        $selector
	 * @param \DOMNode|null $contextNode
	 * @param string        $axis
	 *
	 * @return \chillerlan\PrototypeDOM\NodeList
	 */
	public function querySelectorAll(string $selector, DOMNode $contextNode = null, string $axis = 'descendant-or-self::'):NodeList{
		return $this->query($this->selector2xpath($selector, $axis), $contextNode);
	}

	/**
	 * @param string|array  $selectors
	 * @param \DOMNode|null $contextNode
	 * @param string        $axis
	 * @param int           $nodeType
	 *
	 * @return \chillerlan\PrototypeDOM\NodeList
	 */
	public function select($selectors = null, DOMNode $contextNode = null, string $axis = 'descendant-or-self::', int $nodeType = XML_ELEMENT_NODE):NodeList{

		if(is_string($selectors)){
			$selectors = [trim($selectors)];
		}

		if(!is_array($selectors) || empty($selectors)){
			$selectors = ['*'];
		}

		$elements = new NodeList;

		foreach($selectors as $selector){

			foreach($this->querySelectorAll($selector, $contextNode, $axis) as $element){

				if($element->nodeType === $nodeType){
					$elements[] = $element;
				}

			}

		}

		return $elements;
	}

	public function _loadHTMLFragment(string $content):NodeList{
		$document = new Document;
		$document->loadHTML('<html><body id="-import-content">'.$content.'</body></html>');

		return new NodeList($document->getElementById('-import-content')->childNodes);

/*
		$document->loadHTML('<!DOCTYPE html>' .$content);
		return $document->getElementsByTagName('head')[0]->childNodes
		         ?? $document->getElementsByTagName('body')[0]->childNodes;
*/
	}

	/**
	 * @param \DOMNode|null $context
	 * @param bool          $xml
	 *
	 * @return string
	 */
	public function inspect(DOMNode $context = null, $xml = false):string{
		return $xml
			? $this->saveXML($context)
			: $this->saveHTML($context);
	}

	/**
	 * @param string|array  $selectors
	 * @param \DOMNode|null $contextNode
	 * @param string        $axis
	 *
	 * @return \chillerlan\PrototypeDOM\Document
	 */
	public function removeElementsBySelector($selectors, DOMNode $contextNode = null, string $axis = 'descendant-or-self::'):Document{
		$nodes = $this->select($selectors, $contextNode, $axis);

		if(count($nodes) > 0){
			/** @var \chillerlan\PrototypeDOM\Element $node */
			foreach($nodes as $node){
				$node->remove();
			}

		}

		return $this;
	}

	/**
	 * @param string|\DOMNode|\DOMNodeList|\chillerlan\PrototypeDOM\NodeList $content
	 *
	 * @return \chillerlan\PrototypeDOM\NodeList
	 * @throws \Exception
	 */
	public function _toNodeList($content):NodeList{

		if($content instanceof NodeList || $content instanceof DOMNodeList || is_array($content)){
			return new NodeList($content);
		}
		elseif($content instanceof DOMNode){
			return new NodeList([$content]);
		}
		elseif(is_string($content)){
			return $this->_loadHTMLFragment($content);
		}
		else{
			throw new \Exception('invalid content'); // @codeCoverageIgnore
		}

	}

	/**
	 * @param \DOMNode $element
	 * @param string   $property
	 * @param int      $maxLength
	 * @param int      $nodeType
	 *
	 * @return \chillerlan\PrototypeDOM\NodeList
	 */
	public function recursivelyCollect(DOMNode $element, string $property, int $maxLength = -1, int $nodeType = XML_ELEMENT_NODE):NodeList{
		$nodes = new NodeList;

		if(in_array($property, ['parentNode', 'previousSibling', 'nextSibling'])){

			while($element = $element->{$property}){

				if($element->nodeType === $nodeType){
					$nodes[] = $element;
				}

				if(count($nodes) === $maxLength){
					break;
				}

			}

		}

		return $nodes;
	}

	/**
	 * @param \DOMNode    $element
	 * @param string      $property
	 * @param string|null $selector
	 * @param int         $index
	 * @param int         $nodeType
	 *
	 * @return \DOMNode|null
	 */
	public function _recursivelyFind(DOMNode $element, string $property, string $selector = null, int $index = 0, int $nodeType = XML_ELEMENT_NODE){

		if(in_array($property, ['parentNode', 'previousSibling', 'nextSibling'])){

			/** @var \chillerlan\PrototypeDOM\Element $element */
			while($element = $element->{$property}){

				if($element->nodeType !== $nodeType || !is_null($selector) && !$element->match($selector) || --$index >= 0){
					continue;
				}

				return $element;
			}

		}

		return null;
	}

	/**
	 * @param \DOMNode $element
	 * @param string   $selector
	 *
	 * @return bool
	 */
	public function match(DOMNode $element, string $selector):bool{

		foreach($this->select($selector) as $match){

			if($element->isSameNode($match)){
				return true;
			}

		}

		return false;
	}

	/**
	 * @param string     $tag
	 * @param array|null $attributes
	 *
	 * @return \chillerlan\PrototypeDOM\Element
	 */
	public function newElement(string $tag, array $attributes = null):Element{
		/** @var \chillerlan\PrototypeDOM\Element $element */
		$element = $this->createElement($tag);

		if($attributes){
			$element->setAttributes($attributes);
		}

		return $element;
	}

}
