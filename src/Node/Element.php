<?php
/**
 * Class Element
 *
 * @filesource   Element.php
 * @created      05.05.2017
 * @package      chillerlan\PrototypeDOM\Node
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2017 Smiley
 * @license      MIT
 */

namespace chillerlan\PrototypeDOM\Node;

use DOMElement;

class Element extends DOMElement implements PrototypeHTMLElement{
	use PrototypeHTMLElementTrait;

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
	 * @return \chillerlan\PrototypeDOM\Node\PrototypeHTMLElement
	 */
	public function setAttributes(array $attributes):PrototypeHTMLElement{

		foreach($attributes as $name => $value){
			$this->setAttribute($name, $value);
		}

		return $this;
	}

	/**
	 * @param array $attributes
	 *
	 * @return \chillerlan\PrototypeDOM\Node\PrototypeHTMLElement
	 */
	public function removeAttributes(array $attributes):PrototypeHTMLElement{

		foreach($attributes as $name){
			$this->removeAttribute($name);
		}

		return $this;
	}

	/**
	 * @param array $classnames
	 *
	 * @return \chillerlan\PrototypeDOM\Node\PrototypeHTMLElement
	 */
	public function addClassNames(array $classnames):PrototypeHTMLElement{
		$currentClassnames = $this->classNames();

		foreach($classnames as $classname){

			if(!\in_array($classname, $currentClassnames, true)){
				$currentClassnames[] = $classname;
			}

		}

		$this->class = \implode(' ', \array_unique($currentClassnames));

		return $this;
	}

	/**
	 * @param array $classnames
	 *
	 * @return \chillerlan\PrototypeDOM\Node\PrototypeHTMLElement
	 */
	public function removeClassNames(array $classnames):PrototypeHTMLElement{
		$currentClassnames = $this->classNames();

		foreach($classnames as $classname){
			$keys = \array_keys($currentClassnames, $classname);

			foreach($keys as $key){
				unset($currentClassnames[$key]);
			}

		}

		$this->class = \implode(' ', \array_unique($currentClassnames));

		return $this;
	}

	/**
	 * @return string[]
	 */
	public function getStyles():array{

		if(!$this->hasAttributes()){
			return [];
		}

		$styles = \explode(';', \trim($this->getAttribute('style')));

		$currentStyle = [];

		foreach($styles as $style){
			$s = \explode(':', $style);

			if(\count($s) === 2){
				$currentStyle[\strtolower(\trim($s[0]))] = \trim($s[1]);
			}

		}

		return $currentStyle;
	}

}
