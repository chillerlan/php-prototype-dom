<?php
/**
 * Class CharacterData
 *
 * @filesource   CharacterData.php
 * @created      07.05.2017
 * @package      chillerlan\PrototypeDOM\Node
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2017 Smiley
 * @license      MIT
 */

namespace chillerlan\PrototypeDOM\Node;

use DOMCharacterData;
use chillerlan\PrototypeDOM\Traits\ElementTrait;

class CharacterData extends DOMCharacterData implements PrototypeElement{
	use ElementTrait;
}