<?php
/**
 * Class Comment
 *
 * @filesource   Comment.php
 * @created      08.05.2017
 * @package      chillerlan\PrototypeDOM\Node
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2017 Smiley
 * @license      MIT
 */

namespace chillerlan\PrototypeDOM\Node;

use chillerlan\PrototypeDOM\Traits\NodeTrait;
use DOMComment;

class Comment extends DOMComment implements PrototypeNode{
	use NodeTrait;
}