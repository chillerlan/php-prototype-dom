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

use chillerlan\PrototypeDOM\Node\{
	Attr, CdataSection, CharacterData, Comment, DocumentFragment, DocumentType, Element, Entity,
	EntityReference, Node, Notation, ProcessingInstruction, PrototypeHTMLElement, PrototypeNode, Text,
};
use DOMDocument, DOMException, DOMNode, DOMNodeList, DOMXPath;
use Symfony\Component\CssSelector\CssSelectorConverter;

use function count, in_array, is_file, is_iterable, is_readable, is_string;

use const LIBXML_COMPACT, LIBXML_HTML_NODEFDTD, LIBXML_HTML_NOIMPLIED, LIBXML_NOERROR, LIBXML_NONET, XML_ELEMENT_NODE;

/**
 *
 */
class Document extends DOMDocument{

	protected const NODE_CLASSES = [
		'DOMAttr'                  => Attr::class,
		'DOMCdataSection'          => CdataSection::class,
		'DOMCharacterData'         => CharacterData::class,
		'DOMComment'               => Comment::class,
		'DOMDocumentFragment'      => DocumentFragment::class,
		'DOMDocumentType'          => DocumentType::class,
		'DOMElement'               => Element::class,
		'DOMEntity'                => Entity::class,
		'DOMEntityReference'       => EntityReference::class,
		'DOMNode'                  => Node::class,
		'DOMNotation'              => Notation::class,
		'DOMProcessingInstruction' => ProcessingInstruction::class,
		'DOMText'                  => Text::class,
	];

	protected const LOAD_OPTIONS = LIBXML_COMPACT | LIBXML_NONET | LIBXML_HTML_NODEFDTD | LIBXML_HTML_NOIMPLIED | LIBXML_NOERROR;

	protected CssSelectorConverter $cssSelectorConverter;

	/**
	 * Document constructor.
	 *
	 * @param string|\DOMNodeList|\chillerlan\PrototypeDOM\NodeList $content
	 * @param bool|null                                             $xml
	 * @param string|null                                           $version
	 * @param string|null                                           $encoding
	 */
	public function __construct($content = null, bool $xml = null, string $version = null, string $encoding = null){
		parent::__construct($version ?? '1.0', $encoding ?? 'UTF-8');

		foreach($this::NODE_CLASSES as $baseClass => $extendedClass){
			$this->registerNodeClass($baseClass, $extendedClass);
		}

		if($content !== null){
			$this->loadDocument($content, $xml);
		}

		$this->cssSelectorConverter = new CssSelectorConverter;
	}

	/**
	 * @param string|\DOMNodeList|\chillerlan\PrototypeDOM\NodeList $content
	 * @param bool|null $xml
	 *
	 * @return \chillerlan\PrototypeDOM\Document
	 * @throws \DOMException
	 */
	public function loadDocument($content, bool $xml = null):Document{

		if($content instanceof NodeList){
			return $this->insertNodeList($content);
		}

		if($content instanceof DOMNodeList){
			return $this->insertNodeList(new NodeList($content));
		}

		if(!is_string($content)){
			throw new DOMException('invalid document content');
		}

		if(is_file($content) && is_readable($content)){
			return $this->loadDocumentFile($content, $xml);
		}

		return $this->loadDocumentString($content, $xml);
	}

	/**
	 * @throws \DOMException
	 */
	public function loadDocumentFile(string $file, bool $xml = null, int $options = null):Document{
		$options = $options ?? $this::LOAD_OPTIONS;

		$result = $xml === true
			? $this->load($file, $options)
			: $this->loadHTMLFile($file, $options);

		if($result === false){
			throw new DOMException('failed to load document from file: '.$file); // @codeCoverageIgnore
		}

		return $this;
	}

	/**
	 * @throws \DOMException
	 */
	public function loadDocumentString(string $documentSource, bool $xml = null, int $options = null):Document{
		$options = $options ?? $this::LOAD_OPTIONS;

		$result = $xml === true
			? $this->loadXML($documentSource, $options)
			: $this->loadHTML($documentSource, $options);

		if($result === false){
			throw new DOMException('failed to load document from string'); // @codeCoverageIgnore
		}

		return $this;
	}

	/**
	 * @throws \DOMException
	 */
	public function toNodeList($content):NodeList{

		if($content instanceof NodeList){
			return $content;
		}

		if($content instanceof DOMNode || $content instanceof PrototypeNode){
			return new NodeList([$content]);
		}

		if($content instanceof DOMNodeList || is_iterable($content)){
			return new NodeList($content);
		}

		if(is_string($content)){
			$document = new self;
			$document->loadHTML('<html lang="en"><body id="-import-content">'.$content.'</body></html>');

			return $document->toNodeList($document->getElementById('-import-content')->childNodes);
		}

		throw new DOMException('invalid content');
	}

	/***********
	 * generic *
	 ***********/

	/**
	 *
	 */
	public function selector2xpath(string $selector, string $axis = null):string{
		return $this->cssSelectorConverter->toXPath($selector, $axis ?? '//');
	}

	/**
	 *
	 */
	public function query(string $xpath, DOMNode $contextNode = null):?NodeList{
		$q = (new DOMXPath($this))->query($xpath, $contextNode);

		return $q !== false ? new NodeList($q) : null;
	}

	/**
	 *
	 */
	public function querySelectorAll(string $selector, DOMNode $contextNode = null, string $axis = null):?NodeList{
		return $this->query($this->cssSelectorConverter->toXPath($selector, $axis ?? 'descendant-or-self::'), $contextNode);
	}

	/**
	 *
	 */
	public function removeElementsBySelector(array $selectors, DOMNode $contextNode = null, string $axis = null):Document{
		$nodes = $this->select($selectors, $contextNode, $axis ?? 'descendant-or-self::');

		if(count($nodes) > 0){

			foreach($nodes as $node){
				$node->removeNode();
			}

		}

		return $this;
	}

	/**
	 *
	 */
	public function insertNodeList(NodeList $nodeList):Document{

		foreach($nodeList as $node){
			$this->appendChild($this->importNode($node->cloneNode(true), true));
		}

		return $this;
	}

	/**
	 * @inheritDoc
	 *
	 * @return \chillerlan\PrototypeDOM\Node\PrototypeHTMLElement|\DOMNode|null
	 * @throws \DOMException
	 */
	public function getElementById($elementId):?DOMNode{

		if(!is_string($elementId)){
			throw new DOMException('invalid element id');
		}

		return $this->select(['#'.$elementId])[0] ?? null;
	}

	/**
	 *
	 */
	public function getElementsByClassName(string $className):NodeList{
		return $this->select(['.'.$className]);
	}

	/*************
	 * prototype *
	 *************/

	/**
	 * @link http://api.prototypejs.org/dom/Element/inspect/
	 */
	public function inspect(DOMNode $context = null, bool $xml = null):string{

		if($xml === true){
			return $this->saveXML($context);
		}

		return $this->saveHTML($context);
	}

	/**
	 * @link http://api.prototypejs.org/dom/Element/select/
	 * @see https://secure.php.net/manual/dom.constants.php
	 */
	public function select(array $selectors = null, DOMNode $contextNode = null, string $axis = null, int $nodeType = null):NodeList{
		$nodeType = $nodeType ?? XML_ELEMENT_NODE;
		$elements = new NodeList;

		foreach($selectors ?? ['*'] as $selector){

			if(!is_string($selector)){
				continue;
			}

			foreach($this->querySelectorAll($selector, $contextNode, $axis ?? 'descendant-or-self::') as $element){

				if($element->nodeType === $nodeType){
					$elements[] = $element;
				}

			}

		}

		return $elements;
	}

	/**
	 * @see http://api.prototypejs.org/dom/Element/recursivelyCollect/
	 * @see https://secure.php.net/manual/dom.constants.php
	 */
	public function recursivelyCollect(DOMNode $element, string $property, int $maxLength = null, int $nodeType = null):NodeList{
		$nodeType  = $nodeType ?? XML_ELEMENT_NODE;
		$maxLength = $maxLength ?? -1;
		$nodes     = new NodeList;

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
	 * @see https://secure.php.net/manual/dom.constants.php
	 */
	public function recursivelyFind(PrototypeNode $element, string $property = null, string $selector = null, int $index = null, int $nodeType = null):?DOMNode{
		$nodeType = $nodeType ?? XML_ELEMENT_NODE;
		$index    = $index ?? 0;

		if(in_array($property, ['parentNode', 'previousSibling', 'nextSibling'])){

			while($element = $element->{$property}){

				if($element->nodeType !== $nodeType || $selector !== null && !$element->match($selector) || --$index >= 0){
					continue;
				}

				return $element;
			}

		}

		return null;
	}

	/**
	 * @link http://api.prototypejs.org/dom/Element/match/
	 */
	public function match(DOMNode $element, string $selector):bool{

		foreach($this->select([$selector]) as $match){

			if($element->isSameNode($match)){
				return true;
			}

		}

		return false;
	}

	/**
	 * @link http://api.prototypejs.org/dom/Element/new/
	 */
	public function newElement(string $tag, array $attributes = null):PrototypeHTMLElement{
		/** @var \chillerlan\PrototypeDOM\Node\PrototypeHTMLElement $element */
		$element = $this->createElement($tag);

		if($attributes !== null){
			$element->setAttributes($attributes);
		}

		return $element;
	}

}
