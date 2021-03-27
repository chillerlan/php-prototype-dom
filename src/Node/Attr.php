<?php
/**
 * Class Attr
 *
 * @filesource   Attr.php
 * @created      08.05.2017
 * @package      chillerlan\PrototypeDOM\Node
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2017 Smiley
 * @license      MIT
 */

namespace chillerlan\PrototypeDOM\Node;

use DOMAttr;

class Attr extends DOMAttr implements PrototypeNode{
	use PrototypeNodeTrait;
}
