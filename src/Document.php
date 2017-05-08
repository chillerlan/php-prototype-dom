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
	 * @return \DOMNodeList
	 */
	public function query(string $xpath, DOMNode $contextNode = null):DOMNodeList{
		return (new DOMXPath($this))->query($xpath, $contextNode);
	}

	/**
	 * @param string        $selector
	 * @param \DOMNode|null $contextNode
	 * @param string        $axis
	 *
	 * @return \DOMNodeList
	 */
	public function getElementsBySelector(string $selector, DOMNode $contextNode = null, string $axis = 'descendant-or-self::'):DOMNodeList{
		return $this->query($this->selector2xpath($selector, $axis), $contextNode);
	}

	/**
	 * @param string|array  $selectors
	 * @param \DOMNode|null $contextNode
	 * @param string        $axis
	 * @param int           $nodeType
	 *
	 * @return array
	 */
	public function select($selectors = null, DOMNode $contextNode = null, string $axis = 'descendant-or-self::', int $nodeType = XML_ELEMENT_NODE):array{

		if(is_string($selectors)){
			$selectors = [trim($selectors)];
		}

		if(!is_array($selectors) || empty($selectors)){
			$selectors = ['*'];
		}

		$elements = [];

		foreach($selectors as $selector){

			foreach($this->getElementsBySelector($selector, $contextNode, $axis) as $element){

				if($element->nodeType === $nodeType){
					$elements[] = $element;
				}

			}

		}

		return $elements;
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
	 * @param string|\DOMNode|\DOMNodeList $content
	 *
	 * @return \DOMNodeList
	 * @throws \Exception
	 */
	public function _toDOMNodeList($content):DOMNodeList{

		if($content instanceof DOMNodeList){
			return $content;
		}

		$document = new Document;

		if($content instanceof DOMNode){
			$document->loadHTML('<html><body id="content"></body></html>');

			$document->getElementById('content')->appendChild($document->importNode($content, true));
		}
		elseif(is_string($content)){
			$document->loadHTML('<html><body id="content">'.$content.'</body></html>');
		}
		else{
			throw new \Exception('invalid content'); // @codeCoverageIgnore
		}

		return $document->getElementById('content')->childNodes;
	}

	/**
	 * @param \DOMNode $element
	 * @param string   $property
	 * @param int      $maxLength
	 * @param int      $nodeType
	 *
	 * @return array[\chillerlan\PrototypeDOM\Element]
	 */
	public function recursivelyCollect(DOMNode $element, string $property, int $maxLength = -1, int $nodeType = XML_ELEMENT_NODE):array{
		$nodes = [];

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
	 * @return \chillerlan\PrototypeDOM\Element|\DOMNode|null
	 */
	public function _recursivelyFind(DOMNode $element, string $property, string $selector = null, int $index = 0, int $nodeType = XML_ELEMENT_NODE){

		if(in_array($property, ['parentNode', 'previousSibling', 'nextSibling'])){

			while($element = $element->{$property}){

				/** @var \chillerlan\PrototypeDOM\Element $element */
				if($element->nodeType !== $nodeType || $selector && !$element->match($selector) || --$index >= 0){
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
