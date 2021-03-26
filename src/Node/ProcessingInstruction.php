<?php
/**
 * Class ProcessingInstruction
 *
 * @filesource   ProcessingInstruction.php
 * @created      23.03.2019
 * @package      chillerlan\PrototypeDOM\Node
 * @author       smiley <smiley@chillerlan.net>
 * @copyright    2019 smiley
 * @license      MIT
 */

namespace chillerlan\PrototypeDOM\Node;

use DOMProcessingInstruction;

class ProcessingInstruction extends DOMProcessingInstruction implements PrototypeNode{
	use PrototypeNodeTrait;
}
