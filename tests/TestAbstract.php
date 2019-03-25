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
	protected $dom;

	/**
	 * @var \chillerlan\PrototypeDOM\Node\Element
	 */
	protected $el;

	protected function setUp():void{
		$this->dom               = new Document;
		$this->dom->formatOutput = true;
		$this->dom->loadHTMLFile(__DIR__.'/test.html');
	}

}
