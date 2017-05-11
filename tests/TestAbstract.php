<?php
/**
 * Class TestAbstract
 *
 * @filesource   TestAbstract.php
 * @created      05.05.2017
 * @package      chillerlan\PrototypeDOMTest
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2017 Smiley
 * @license      MIT
 */

namespace chillerlan\PrototypeDOMTest;

use chillerlan\PrototypeDOM\Document;
use chillerlan\PrototypeDOM\Node\Element;
use PHPUnit\Framework\TestCase;

abstract class TestAbstract extends TestCase{

	/**
	 * @var \chillerlan\PrototypeDOM\Document
	 */
	protected $document;

	/**
	 * @var \chillerlan\PrototypeDOM\Node\Element
	 */
	protected $element;

	protected function setUp(){
		$this->document               = new Document;
		$this->document->formatOutput = true;
		$this->document->loadHTMLFile(__DIR__.'/test.html');

		$this->element = $this->document->select('#what')[0];
	}

	public function getID(Element $e){
		return $e->id;
	}

	/**
	 * @param $id
	 *
	 * @return \chillerlan\PrototypeDOM\Node\Element
	 */
	protected function _e($id){
		return $this->document->getElementById($id);
	}

}
