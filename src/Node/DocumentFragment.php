<?php
/**
 * Class DocumentFragment
 *
 * @created      07.05.2017
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2017 Smiley
 * @license      MIT
 */

namespace chillerlan\PrototypeDOM\Node;

use DOMDocumentFragment;

class DocumentFragment extends DOMDocumentFragment implements PrototypeElement{
	use PrototypeElementTrait;
}
