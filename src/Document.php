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
use chillerlan\PrototypeDOM\Node\{Element, PrototypeNode};
use chillerlan\Traits\Magic;
use Symfony\Component\CssSelector\CssSelectorConverter;

/**
 * @property string $title
 */
class Document extends DOMDocument{
	use Magic;

	const NODE_CLASSES = [
		'Attr',
		'CharacterData',
		'Comment',
		'DocumentFragment',
		'DocumentType',
		'Element',
		'Text',
	];

	/**
	 * Document constructor.
	 *
	 * @param string      $content
	 * @param bool        $xml
	 * @param string|null $version
	 * @param string|null $encoding
	 */
	public function __construct($content = null, $xml = false, $version = '1.0', $encoding = 'UTF-8'){
		parent::__construct($version, $encoding);

		foreach(self::NODE_CLASSES as $nodeClass){
			$this->registerNodeClass('DOM'.$nodeClass, __NAMESPACE__.'\\Node\\'.$nodeClass);
		}

		if(!is_null($content)){
			$this->_loadDocument($content, $xml);
		}

	}


	/*********
	 * magic *
	 *********/

	public function magic_get_title(){
		return $this->select('head > title')->item(0)->nodeValue ?? null;
	}

	public function magic_set_title(string $title){
		$currentTitle = $this->select('head > title')->item(0);

		if($currentTitle instanceof Element){
			$currentTitle->update($title);
		}
		else{
			$head         = $this->select('head')->item(0);
			$currentTitle = $this->newElement('title')->update($title);

			if(!$head){
				$head = $this->appendChild($this->newElement('head'));
			}

			$head->insert($currentTitle);
		}
	}



	/********
	 * ugly *
	 ********/

	public function _loadDocument($content, $xml = false){

		switch(true){
			case $content instanceof NodeList   : return $this->insertNodeList($content);
			case $content instanceof DOMNodeList: return $this->insertNodeList(new NodeList($content));
			case is_string($content)            : return $this->_loadDocumentString($content, $xml);
			default: return $this;
		}
	}

	public function _loadDocumentString(string $documentSource, bool $xml = false){
		$options = LIBXML_COMPACT|LIBXML_NONET;

		$xml
			? $this->loadXML($documentSource, $options)
			: $this->loadHTML($documentSource, $options);

		return $this;
	}

	/**
	 * @param mixed $content
	 *
	 * @return \chillerlan\PrototypeDOM\NodeList
	 * @throws \Exception
	 */
	public function _toNodeList($content):NodeList{

		switch(true){
			case $content instanceof NodeList   : return $content;
			case $content instanceof DOMNodeList: return new NodeList($content);
			case $content instanceof DOMNode    : return $this->_arrayToNodeList([$content]);
			case is_array($content)             : return $this->_arrayToNodeList($content);
			case is_string($content)            : return $this->_HTMLFragmentToNodeList($content);
			default:
				throw new \Exception('invalid content'); // @codeCoverageIgnore
		}

	}

	/**
	 * @param string $content
	 *
	 * @return \chillerlan\PrototypeDOM\NodeList
	 */
	public function _HTMLFragmentToNodeList(string $content):NodeList{
		$document = new Document;
		$document->loadHTML('<html><body id="-import-content">'.$content.'</body></html>');

		return $document->_toNodeList($document->getElementById('-import-content')->childNodes);
	}

	/**
	 * @param array $array
	 *
	 * @return \chillerlan\PrototypeDOM\NodeList
	 */
	public function _arrayToNodeList(array $array):NodeList{
		$nodelist = new NodeList;

		foreach($array as $node){
			$nodelist[] = $node;
		}

		return $nodelist;
	}


	/***********
	 * generic *
	 ***********/

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
	 *
	 * @return \chillerlan\PrototypeDOM\Document
	 */
	public function removeElementsBySelector($selectors, DOMNode $contextNode = null, string $axis = 'descendant-or-self::'):Document{
		$nodes = $this->select($selectors, $contextNode, $axis);

		if(count($nodes) > 0){
			/** @var \chillerlan\PrototypeDOM\Node\Element $node */
			foreach($nodes as $node){
				$node->remove();
			}

		}

		return $this;
	}

	/**
	 * @param \chillerlan\PrototypeDOM\NodeList $nodeList
	 *
	 * @return \chillerlan\PrototypeDOM\Document
	 */
	public function insertNodeList(NodeList $nodeList):Document{

		/** @var \DOMNode $node */
		foreach($nodeList as $node){
			$this->appendChild($this->importNode($node->cloneNode(true), true));
		}

		return $this;
	}

	/*************
	 * prototype *
	 *************/

	/**
	 * @link http://api.prototypejs.org/dom/Element/inspect/
	 *
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
	 * @link http://api.prototypejs.org/dom/Element/select/
	 *
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

	/**
	 * @link http://api.prototypejs.org/dom/Element/recursivelyCollect/
	 *
	 * @param \chillerlan\PrototypeDOM\Node\PrototypeNode $element
	 * @param string                                      $property
	 * @param int                                         $maxLength
	 * @param int                                         $nodeType
	 *
	 * @return \chillerlan\PrototypeDOM\NodeList
	 */
	public function recursivelyCollect(PrototypeNode $element, string $property, int $maxLength = -1, int $nodeType = XML_ELEMENT_NODE):NodeList{
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
	 * @param \chillerlan\PrototypeDOM\Node\PrototypeNode $element
	 * @param string                                      $property
	 * @param string|null                                 $selector
	 * @param int                                         $index
	 * @param int                                         $nodeType
	 *
	 * @return \chillerlan\PrototypeDOM\Node\PrototypeElement|null
	 */
	public function _recursivelyFind(PrototypeNode $element, string $property, string $selector = null, int $index = 0, int $nodeType = XML_ELEMENT_NODE){

		if(in_array($property, ['parentNode', 'previousSibling', 'nextSibling'])){

			/** @var \chillerlan\PrototypeDOM\Node\Element $element */
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
	 * @link http://api.prototypejs.org/dom/Element/match/
	 *
	 * @param \chillerlan\PrototypeDOM\Node\PrototypeNode $element
	 * @param string                                      $selector
	 *
	 * @return bool
	 */
	public function match(PrototypeNode $element, string $selector):bool{

		foreach($this->select($selector) as $match){

			if($element->isSameNode($match)){
				return true;
			}

		}

		return false;
	}

	/**
	 * @link http://api.prototypejs.org/dom/Element/new/
	 *
	 * @param string     $tag
	 * @param array|null $attributes
	 *
	 * @return \chillerlan\PrototypeDOM\Node\Element
	 */
	public function newElement(string $tag, array $attributes = null):Element{
		/** @var \chillerlan\PrototypeDOM\Node\Element $element */
		$element = $this->createElement($tag);

		if(!is_null($attributes)){
			$element->setAttributes($attributes);
		}

		return $element;
	}

}
