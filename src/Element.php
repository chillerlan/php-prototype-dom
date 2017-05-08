<?php
/**
 * Class Element
 *
 * @filesource   Element.php
 * @created      05.05.2017
 * @package      chillerlan\PrototypeDOM
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2017 Smiley
 * @license      MIT
 */

namespace chillerlan\PrototypeDOM;

use DOMElement, DOMNode, DOMNodeList;

class Element extends DOMElement{
	use NodeTraversalTrait, NodeManipulationTrait;

	/**
	 * @param string|null $newID
	 *
	 * @return string
	 */
	public function id(string $newID = null):string {
		$oldID = $this->getAttribute('id');

		if($newID){
			$this->setAttribute('id', $newID);
		}

		return $oldID;
	}

	/**
	 * @return array
	 */
	public function getClassNames():array{
		$currentClassnames = [];

		if($this->hasAttributes()){
			$classnames = explode(' ', trim($this->getAttribute('class')));

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
	 * @param string $classname
	 *
	 * @return bool
	 */
	public function hasClassName(string $classname):bool{
		return in_array($classname, $this->getClassNames(), true);
	}

	/**
	 * @param array $classnames
	 *
	 * @return \chillerlan\PrototypeDOM\Element
	 */
	public function addClassNames(array $classnames):Element{
		$currentClassnames = $this->getClassNames();

		foreach($classnames as $classname){

			if(!in_array($classname, $currentClassnames, true)){
				array_push($currentClassnames, $classname);
			}

		}

		$this->setAttribute('class', implode(' ', $currentClassnames));

		return $this;
	}

	/**
	 * @param array $classnames
	 *
	 * @return \chillerlan\PrototypeDOM\Element
	 */
	public function removeClassNames(array $classnames):Element{
		$currentClassnames = $this->getClassNames();

		// @todo -> regex?
		foreach($classnames as $classname){
			$keys = array_keys($currentClassnames, $classname);

			if(!empty($keys)){

				foreach($keys as $key){
					unset($currentClassnames[$key]);
				}

			}

		}

		$this->setAttribute('class', implode(' ', $currentClassnames));

		return $this;
	}

	/**
	 * @param string $classname
	 *
	 * @return \chillerlan\PrototypeDOM\Element
	 */
	public function addClassName(string $classname):Element{
		return $this->addClassNames([$classname]);
	}

	/**
	 * @param string $classname
	 *
	 * @return \chillerlan\PrototypeDOM\Element
	 */
	public function removeClassName(string $classname):Element{
		return $this->removeClassNames([$classname]);
	}

	/**
	 * @param string $classname
	 *
	 * @return \chillerlan\PrototypeDOM\Element
	 */
	public function toggleClassName(string $classname):Element{

		return $this->hasClassName($classname)
			? $this->removeClassName($classname)
			: $this->addClassName($classname);
	}

	/**
	 * @return array
	 */
	public function getStyle():array{
		$currentStyle = [];

		if($this->hasAttributes()){
			$styles = explode(';', trim($this->getAttribute('style')));

			if(!empty($styles)){

				foreach($styles as $style){
					$s = explode(':', $style);

					if(count($s) === 2){
						$currentStyle[strtolower(trim($s[0]))] = trim($s[1]);
					}

				}

			}

		}

		return $currentStyle;
	}

	/**
	 * @param string $property
	 *
	 * @return bool|string
	 */
	public function hasStyle(string $property){
		$currentStyle = $this->getStyle();

		if(array_key_exists(strtolower($property), $currentStyle)){
			return $currentStyle[$property];
		}

		return false;
	}

	/**
	 * @param array $style
	 * @param bool  $replace
	 *
	 * @return \chillerlan\PrototypeDOM\Element
	 */
	public function setStyle(array $style, bool $replace = false):Element{
		$currentStyle = $this->getStyle();

		if(!$replace){
			$style = array_merge($currentStyle, $style);
		}

		foreach($style as $property => $value){
			$style[$property] = $property.': '.$value.';';
		}

		$this->setAttribute('style', implode(' ', $style));

		return $this;
	}

	/**
	 * @return array
	 */
	public function getAttributes():array{
		$attributes = [];

		foreach($this->attributes as $attribute){
			$attributes[$attribute->nodeName] = $attribute->nodeValue;
		}

		return $attributes;
	}

	/**
	 * @param array $attributes
	 *
	 * @return \chillerlan\PrototypeDOM\Element
	 */
	public function setAttributes(array $attributes):Element{

		foreach($attributes as $name => $value){
			$this->setAttribute($name, $value);
		}

		return $this;
	}

	/**
	 * @param array $attributes
	 *
	 * @return \chillerlan\PrototypeDOM\Element
	 */
	public function removeAttributes(array $attributes):Element{

		foreach($attributes as $name){
			$this->removeAttribute($name);
		}

		return $this;
	}

}
