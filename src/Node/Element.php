<?php
/**
 * Class Element
 *
 * @created      05.05.2017
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2017 Smiley
 * @license      MIT
 */

namespace chillerlan\PrototypeDOM\Node;

use DOMElement;

class Element extends DOMElement implements PrototypeHTMLElement{
	use PrototypeHTMLElementTrait;
}
