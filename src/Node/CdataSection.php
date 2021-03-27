<?php
/**
 * Class CdataSection
 *
 * @created      21.03.2019
 * @author       smiley <smiley@chillerlan.net>
 * @copyright    2019 smiley
 * @license      MIT
 */

namespace chillerlan\PrototypeDOM\Node;

use DOMCdataSection;

class CdataSection extends DOMCdataSection implements PrototypeNode{
	use PrototypeNodeTrait;
}
