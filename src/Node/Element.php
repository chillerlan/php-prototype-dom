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

namespace chillerlan\PrototypeDOM\Node;

use DOMElement;
use chillerlan\PrototypeDOM\Traits\HTMLElementTrait;

class Element extends DOMElement implements PrototypeHTMLElement{
	use HTMLElementTrait;

	/**
	 * @return  string[]
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
	 * @return \chillerlan\PrototypeDOM\Node\Element
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
	 * @return \chillerlan\PrototypeDOM\Node\Element
	 */
	public function removeAttributes(array $attributes):Element{

		foreach($attributes as $name){
			$this->removeAttribute($name);
		}

		return $this;
	}

	/**
	 * @param array $classnames
	 *
	 * @return \chillerlan\PrototypeDOM\Node\Element
	 */
	public function addClassNames(array $classnames):Element{
		$currentClassnames = $this->classNames();

		foreach($classnames as $classname){

			if(!in_array($classname, $currentClassnames, true)){
				array_push($currentClassnames, $classname);
			}

		}

		$this->class = implode(' ', array_unique($currentClassnames));

		return $this;
	}

	/**
	 * @param array $classnames
	 *
	 * @return \chillerlan\PrototypeDOM\Node\Element
	 */
	public function removeClassNames(array $classnames):Element{
		$currentClassnames = $this->classNames();

		foreach($classnames as $classname){
			$keys = array_keys($currentClassnames, $classname);

			if(!empty($keys)){

				foreach($keys as $key){
					unset($currentClassnames[$key]);
				}

			}

		}

		$this->class = implode(' ', array_unique($currentClassnames));

		return $this;
	}

	/**
	 * @return string[]
	 */
	public function getStyles():array{
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

}
