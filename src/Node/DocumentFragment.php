<?php
/**
 * Class DocumentFragment
 *
 * @filesource   DocumentFragment.php
 * @created      07.05.2017
 * @package      chillerlan\PrototypeDOM\Node
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2017 Smiley
 * @license      MIT
 */

namespace chillerlan\PrototypeDOM\Node;

class DocumentFragment extends \DOMDocumentFragment implements PrototypeElement{
	use ElementTrait, NodeTrait;
}
