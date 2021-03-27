<?php
/**
 * Class DocumentType
 *
 * @created      08.05.2017
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2017 Smiley
 * @license      MIT
 */

namespace chillerlan\PrototypeDOM\Node;

use DOMDocumentType;

class DocumentType extends DOMDocumentType implements PrototypeNode{
	use PrototypeNodeTrait;
}
