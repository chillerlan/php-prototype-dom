<?php
/**
 * Class CharacterData
 *
 * @created      07.05.2017
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2017 Smiley
 * @license      MIT
 */

namespace chillerlan\PrototypeDOM\Node;

use DOMCharacterData;

class CharacterData extends DOMCharacterData implements PrototypeElement{
	use PrototypeElementTrait;
}
