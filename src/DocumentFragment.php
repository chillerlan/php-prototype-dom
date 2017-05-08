<?php
/**
 * Class DocumentFragment
 *
 * @filesource   DocumentFragment.php
 * @created      07.05.2017
 * @package      chillerlan\PrototypeDOM
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2017 Smiley
 * @license      MIT
 */

namespace chillerlan\PrototypeDOM;

use DOMDocumentFragment;

/**
 */
class DocumentFragment extends DOMDocumentFragment{
	use NodeTraversalTrait, NodeManipulationTrait;
}
