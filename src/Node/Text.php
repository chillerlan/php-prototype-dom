<?php
/**
 * Class Text
 *
 * @filesource   Text.php
 * @created      06.05.2017
 * @package      chillerlan\PrototypeDOM
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2017 Smiley
 * @license      MIT
 */

namespace chillerlan\PrototypeDOM\Node;

use DOMText;
use chillerlan\PrototypeDOM\Traits\ElementTrait;

class Text extends DOMText implements PrototypeElement{
	use ElementTrait;
}
