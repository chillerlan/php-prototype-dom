<?php
/**
 * Class DocumentType
 *
 * @filesource   DocumentType.php
 * @created      08.05.2017
 * @package      chillerlan\PrototypeDOM
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2017 Smiley
 * @license      MIT
 */

namespace chillerlan\PrototypeDOM\Node;

use chillerlan\PrototypeDOM\Traits\NodeTrait;
use DOMDocumentType;

class DocumentType extends DOMDocumentType implements PrototypeNode{
	use NodeTrait;
}
