<?php
/**
 * Class Attr
 *
 * @created      08.05.2017
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2017 Smiley
 * @license      MIT
 */

namespace chillerlan\PrototypeDOM\Node;

use DOMAttr;

class Attr extends DOMAttr implements PrototypeNode{
	use PrototypeNodeTrait;
}
