<?php
/**
 * Class Comment
 *
 * @created      08.05.2017
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2017 Smiley
 * @license      MIT
 */

namespace chillerlan\PrototypeDOM\Node;

use DOMComment;

class Comment extends DOMComment implements PrototypeNode{
	use PrototypeNodeTrait;
}
