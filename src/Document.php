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
use chillerlan\Traits\Magic;
use DOMDocument, DOMException, DOMNode, DOMNodeList, DOMXPath;
use Symfony\Component\CssSelector\CssSelectorConverter;

/**
 * @property string $title
 */
class Document extends DOMDocument{
	use Magic;

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

	protected const LOAD_OPTIONS = \LIBXML_COMPACT|\LIBXML_NONET|\LIBXML_HTML_NODEFDTD|\LIBXML_HTML_NOIMPLIED|\LIBXML_NOERROR;

	/**
	 * @var \Symfony\Component\CssSelector\CssSelectorConverter
	 */
	protected $cssSelectorConverter;

	/**
	 * Document constructor.
	 *
	 * @param \chillerlan\PrototypeDOM\NodeList|\DOMNodeList|string|null $content
	 * @param bool|null                                                  $xml
	 * @param string|null                                                $version
	 * @param string|null                                                $encoding
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


	/*********
	 * magic *
	 *********/

	public function magic_get_title():?string{
		return $this->select(['head > title'])->item(0)->nodeValue ?? null;
	}

	/**
	 * @param string $title
	 *
	 * @throws \DOMException
	 */
	public function magic_set_title(string $title):void{
		$currentTitle = $this->select(['head > title'])->item(0);

		if($currentTitle instanceof Element){
			$currentTitle->update($title);
			return;
		}

		$head         = $this->select(['head'])->item(0);
		$currentTitle = $this->newElement('title')->update($title);

		if(!$head){
			$html = $this->select(['html'])->first();

			if(!$html instanceof PrototypeHTMLElement){
				throw new DOMException('<html> header missing');
			}

			$head = $this->newElement('head');
			$html->insert_top($head);
		}

		$head->insert($currentTitle);
	}

	/**
	 * @param \chillerlan\PrototypeDOM\NodeList|\DOMNodeList|string $content
	 * @param bool                                                  $xml
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

		if(!\is_string($content)){
			throw new DOMException('invalid document content');
		}

		if(\is_file($content) && \is_readable($content)){
			return $this->loadDocumentFile($content, $xml);
		}

		return $this->loadDocumentString($content, $xml);
	}

	/**
	 * @param string    $file
	 * @param bool|null $xml
	 * @param int|null  $options
	 *
	 * @return \chillerlan\PrototypeDOM\Document
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
	 * @param string   $documentSource
	 * @param bool     $xml
	 * @param int|null $options
	 *
	 * @return \chillerlan\PrototypeDOM\Document
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
	 * @param mixed $content
	 *
	 * @return \chillerlan\PrototypeDOM\NodeList
	 * @throws \Exception
	 */
	public function toNodeList($content):NodeList{

		if($content instanceof NodeList){
			return $content;
		}

		if($content instanceof DOMNode || $content instanceof PrototypeNode){
			return new NodeList([$content]);
		}

		if($content instanceof DOMNodeList || \is_iterable($content)){
			return new NodeList($content);
		}

		if(\is_string($content)){
			$document = new self;
			$document->loadHTML('<html><body id="-import-content">'.$content.'</body></html>');

			return $document->toNodeList($document->getElementById('-import-content')->childNodes);
		}

		throw new DOMException('invalid content');
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
	public function selector2xpath(string $selector, string $axis = null):string{
		return $this->cssSelectorConverter->toXPath($selector, $axis ?? '//');
	}

	/**
	 * @param string        $xpath
	 * @param \DOMNode|null $contextNode
	 *
	 * @return \chillerlan\PrototypeDOM\NodeList|null
	 */
	public function query(string $xpath, DOMNode $contextNode = null):?NodeList{
		$q = (new DOMXPath($this))->query($xpath, $contextNode);

		return $q !== false ? new NodeList($q) : null;
	}

	/**
	 * @param string        $selector
	 * @param \DOMNode|null $contextNode
	 * @param string        $axis
	 *
	 * @return \chillerlan\PrototypeDOM\NodeList|null
	 */
	public function querySelectorAll(string $selector, DOMNode $contextNode = null, string $axis = null):?NodeList{
		return $this->query($this->cssSelectorConverter->toXPath($selector, $axis ?? 'descendant-or-self::'), $contextNode);
	}

	/**
	 * @param string|array  $selectors
	 * @param \DOMNode|null $contextNode
	 * @param string        $axis
	 *
	 * @return \chillerlan\PrototypeDOM\Document
	 */
	public function removeElementsBySelector($selectors, DOMNode $contextNode = null, string $axis = null):Document{
		/** @var \chillerlan\PrototypeDOM\NodeList $nodes */
		$nodes = $this->select($selectors, $contextNode, $axis ?? 'descendant-or-self::');

		if(\count($nodes) > 0){

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
	public function inspect(DOMNode $context = null, bool $xml = null):string{

		if($xml === true){
			return $this->saveXML($context);
		}

		return $this->saveHTML($context);
	}

	/**
	 * @link http://api.prototypejs.org/dom/Element/select/
	 *
	 * @param string|array  $selectors
	 * @param \DOMNode|null $contextNode
	 * @param string        $axis
	 * @param int           $nodeType https://secure.php.net/manual/dom.constants.php
	 *
	 * @return \chillerlan\PrototypeDOM\NodeList
	 */
	public function select(array $selectors = null, DOMNode $contextNode = null, string $axis = null, int $nodeType = \XML_ELEMENT_NODE):NodeList{
		$elements = new NodeList;

		foreach($selectors ?? ['*'] as $selector){

			if(!\is_string($selector)){
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
	 * @link http://api.prototypejs.org/dom/Element/recursivelyCollect/
	 *
	 * @param \chillerlan\PrototypeDOM\Node\PrototypeNode $element
	 * @param string                                      $property
	 * @param int                                         $maxLength
	 * @param int                                         $nodeType https://secure.php.net/manual/dom.constants.php
	 *
	 * @return \chillerlan\PrototypeDOM\NodeList
	 */
	public function recursivelyCollect(PrototypeNode $element, string $property, int $maxLength = -1, int $nodeType = \XML_ELEMENT_NODE):NodeList{
		$nodes = new NodeList;

		if(\in_array($property, ['parentNode', 'previousSibling', 'nextSibling'])){

			while($element = $element->{$property}){

				if($element->nodeType === $nodeType){
					$nodes[] = $element;
				}

				if(\count($nodes) === $maxLength){
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
	 * @param int                                         $nodeType https://secure.php.net/manual/dom.constants.php
	 *
	 * @return \chillerlan\PrototypeDOM\Node\PrototypeNode|null
	 */
	public function recursivelyFind(PrototypeNode $element, string $property = null, string $selector = null, int $index = 0, int $nodeType = \XML_ELEMENT_NODE):?PrototypeNode{

		if(\in_array($property, ['parentNode', 'previousSibling', 'nextSibling'])){

			/** @var \chillerlan\PrototypeDOM\Node\Element $element */
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
	 *
	 * @param \chillerlan\PrototypeDOM\Node\PrototypeNode|\DOMNode $element
	 * @param string                                               $selector
	 *
	 * @return bool
	 */
	public function match(PrototypeNode $element, string $selector):bool{

		/** @var \chillerlan\PrototypeDOM\Node\Element $match */
		foreach($this->select([$selector]) as $match){

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
	 * @return \chillerlan\PrototypeDOM\Node\PrototypeHTMLElement
	 */
	public function newElement(string $tag, array $attributes = null):PrototypeHTMLElement{
		/** @var \chillerlan\PrototypeDOM\Node\Element $element */
		$element = $this->createElement($tag);

		if($attributes !== null){
			$element->setAttributes($attributes);
		}

		return $element;
	}

	/**
	 * @param string $elementId
	 *
	 * @return \chillerlan\PrototypeDOM\Node\PrototypeNode|null
	 * @throws \DOMException
	 */
	public function getElementById($elementId):?PrototypeNode{

		if(!\is_string($elementId)){
			throw new DOMException('invalid element id');
		}

		return $this->select(['#'.$elementId])[0] ?? null;
	}

}
