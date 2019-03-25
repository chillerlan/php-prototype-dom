<?php
/**
 * Class Node
 *
 * @filesource   Node.php
 * @created      24.03.2019
 * @package      chillerlan\PrototypeDOM\Node
 * @author       smiley <smiley@chillerlan.net>
 * @copyright    2019 smiley
 * @license      MIT
 */

namespace chillerlan\PrototypeDOM\Node;

class Node extends \DOMNode implements PrototypeNode{
	use NodeTrait;
}
