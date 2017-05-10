<?php
/**
 * Class CharacterData
 *
 * @filesource   CharacterData.php
 * @created      07.05.2017
 * @package      chillerlan\PrototypeDOM
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2017 Smiley
 * @license      MIT
 */

namespace chillerlan\PrototypeDOM;

use DOMCharacterData;

class CharacterData extends DOMCharacterData {
	use TraversalTrait, ManipulationTrait;
}
