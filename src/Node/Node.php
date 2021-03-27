<?php
/**
 * Class Node
 *
 * @created      24.03.2019
 * @author       smiley <smiley@chillerlan.net>
 * @copyright    2019 smiley
 * @license      MIT
 */

namespace chillerlan\PrototypeDOM\Node;

use DOMNode;

class Node extends DOMNode implements PrototypeNode{
	use PrototypeNodeTrait;
}
