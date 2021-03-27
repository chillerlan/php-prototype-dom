<?php
/**
 * Class Element
 *
 * @filesource   Element.php
 * @created      05.05.2017
 * @package      chillerlan\PrototypeDOM\Node
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2017 Smiley
 * @license      MIT
 */

namespace chillerlan\PrototypeDOM\Node;

use DOMElement;

class Element extends DOMElement implements PrototypeHTMLElement{
	use PrototypeHTMLElementTrait;
}
