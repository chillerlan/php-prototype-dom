<?php
/**
 * Trait HTMLElementTrait
 *
 * @filesource   HTMLElementTrait.php
 * @created      11.05.2017
 * @package      chillerlan\PrototypeDOM\Traits
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2017 Smiley
 * @license      MIT
 */

namespace chillerlan\PrototypeDOM\Traits;

use chillerlan\PrototypeDOM\Node\PrototypeHTMLElement;

/**
 * @property string $id
 * @property string $class
 * @property string $innerHTML
 */
trait HTMLElementTrait{
	use Magic, ElementTrait;

	protected function magic_get_id():string {
		return trim($this->getAttribute('id'));
	}

	protected function magic_set_id($id) {
		return $this->setAttribute('id', $id);
	}

	protected function magic_get_class():string {
		return trim($this->getAttribute('class'));
	}

	protected function magic_set_class($class) {
		return $this->setAttribute('class', $class);
	}

	protected function magic_get_innerHTML():string {
		return $this->inspect();
	}

	/**
	 * http://api.prototypejs.org/dom/Element/identify/
	 *
	 * @param string|null $newID
	 *
	 * @return string
	 */
	public function identify(string $newID = null):string {
		$oldID = $this->id;

		if(!is_null($newID)){
			$this->id = $newID;
		}

		return $oldID;
	}

	/**
	 * @link http://api.prototypejs.org/dom/Element/classNames/
	 *
	 * @return array
	 */
	public function classNames():array{
		$currentClassnames = [];

		if($this->hasAttributes()){
			$classnames = explode(' ', $this->class);

			if(!empty($classnames)){

				foreach($classnames as $classname){

					if(empty($classname)){
						continue;
					}

					$currentClassnames[] = $classname;
				}

			}

		}

		return $currentClassnames;
	}

	/**
	 * @link http://api.prototypejs.org/dom/Element/hasClassName/
	 *
	 * @param string $classname
	 *
	 * @return bool
	 */
	public function hasClassName(string $classname):bool{
		return in_array($classname, $this->classNames(), true);
	}

	/**
	 * @link http://api.prototypejs.org/dom/Element/addClassName/
	 *
	 * @param string $classname
	 *
	 * @return \chillerlan\PrototypeDOM\Node\PrototypeHTMLElement
	 */
	public function addClassName(string $classname):PrototypeHTMLElement{
		return $this->addClassNames([$classname]);
	}

	/**
	 * @link http://api.prototypejs.org/dom/Element/removeClassName/
	 *
	 * @param string $classname
	 *
	 * @return \chillerlan\PrototypeDOM\Node\PrototypeHTMLElement
	 */
	public function removeClassName(string $classname):PrototypeHTMLElement{
		return $this->removeClassNames([$classname]);
	}

	/**
	 * @link http://api.prototypejs.org/dom/Element/toggleClassName/
	 *
	 * @param string $classname
	 *
	 * @return \chillerlan\PrototypeDOM\Node\PrototypeHTMLElement
	 */
	public function toggleClassName(string $classname):PrototypeHTMLElement{

		return $this->hasClassName($classname)
			? $this->removeClassName($classname)
			: $this->addClassName($classname);
	}

	/**
	 * @link http://api.prototypejs.org/dom/Element/getStyle/
	 *
	 * @param string $property
	 *
	 * @return null|string
	 */
	public function getStyle(string $property){
		$currentStyle = $this->getStyles();

		if(array_key_exists(strtolower($property), $currentStyle)){
			return $currentStyle[$property];
		}

		return null;
	}

	/**
	 * @link http://api.prototypejs.org/dom/Element/setStyle/
	 *
	 * @param array $style
	 * @param bool  $replace
	 *
	 * @return \chillerlan\PrototypeDOM\Node\PrototypeHTMLElement
	 */
	public function setStyle(array $style, bool $replace = false):PrototypeHTMLElement{
		$currentStyle = $this->getStyles();

		if(!$replace){
			$style = array_merge($currentStyle, $style);
		}

		foreach($style as $property => $value){
			$style[$property] = $property.': '.$value.';';
		}

		$this->setAttribute('style', implode(' ', $style));

		return $this;
	}


}
