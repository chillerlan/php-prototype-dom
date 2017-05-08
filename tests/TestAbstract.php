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
use PHPUnit\Framework\TestCase;

abstract class TestAbstract extends TestCase{

	/**
	 * @var \chillerlan\PrototypeDOM\Document
	 */
	protected $document;

	/**
	 * @var \chillerlan\PrototypeDOM\Element
	 */
	protected $element;

	protected function setUp(){
		$this->document               = new Document;
		$this->document->formatOutput = true;
		$this->document->loadHTMLFile(__DIR__.'/test.html');

		$this->element = $this->document->select('#what')[0];
	}

}
