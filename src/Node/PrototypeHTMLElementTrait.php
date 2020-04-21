<?php
/**
 * Trait PrototypeHTMLElementTrait
 *
 * @filesource   PrototypeHTMLElementTrait.php
 * @created      11.05.2017
 * @package      chillerlan\PrototypeDOM\Node
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2017 Smiley
 * @license      MIT
 *
 * @noinspection PhpIncompatibleReturnTypeInspection
 */

namespace chillerlan\PrototypeDOM\Node;

use function array_key_exists, array_merge, explode, implode, in_array, strtolower, trim;

/**
 *
 */
trait PrototypeHTMLElementTrait{
	use PrototypeElementTrait;

	public function getID():string{
		return trim($this->getAttribute('id'));
	}

	public function setID(string $id):PrototypeHTMLElement{
		$this->setAttribute('id', $id);

		return $this;
	}

	public function getClassName():string{
		return trim($this->getAttribute('class'));
	}

	public function setClassName(string $class):PrototypeHTMLElement{
		$this->setAttribute('class', $class);

		return $this;
	}

	public function getHref():string{
		return trim($this->getAttribute('href'));
	}

	public function setHref(string $href):PrototypeHTMLElement{
		$this->setAttribute('href', $href);

		return $this;
	}

	public function getSrc():string{
		return trim($this->getAttribute('src'));
	}

	public function setSrc(string $src):PrototypeHTMLElement{
		$this->setAttribute('src', $src);

		return $this;
	}

	/**
	 * @inheritDoc
	 */
	public function identify(string $newID = null):string{
		$oldID = $this->getAttribute('id');

		if($newID !== null){
			$this->setAttribute('id', $newID);
		}

		return $oldID;
	}

	/**
	 * @inheritDoc
	 */
	public function classNames():array{

		if(!$this->hasAttributes()){
			return [];
		}

		$classnames        = explode(' ', $this->getClassName());
		$currentClassnames = [];

		foreach($classnames as $classname){
			$classname = trim($classname);

			if(!empty($classname)){
				$currentClassnames[] = $classname;
			}

		}

		return $currentClassnames;
	}

	/**
	 * @inheritDoc
	 */
	public function hasClassName(string $classname):bool{
		return in_array($classname, $this->classNames(), true);
	}

	/**
	 * @inheritDoc
	 */
	public function addClassName(string $classname):PrototypeHTMLElement{
		return $this->addClassNames([$classname]);
	}

	/**
	 * @inheritDoc
	 */
	public function removeClassName(string $classname):PrototypeHTMLElement{
		return $this->removeClassNames([$classname]);
	}

	/**
	 * @inheritDoc
	 */
	public function toggleClassName(string $classname):PrototypeHTMLElement{

		if($this->hasClassName($classname)){
			return $this->removeClassName($classname);
		}

		return $this->addClassName($classname);
	}

	/**
	 * @inheritDoc
	 */
	public function getStyle(string $property):?string{
		$currentStyle = $this->getStyles();

		if(array_key_exists(strtolower($property), $currentStyle)){
			return $currentStyle[$property];
		}

		return null;
	}

	/**
	 * @inheritDoc
	 */
	public function setStyle(array $style, bool $replace = null):PrototypeHTMLElement{
		$currentStyle = $this->getStyles();

		if($replace !== true){
			$style = array_merge($currentStyle, $style);
		}

		foreach($style as $property => $value){
			$style[$property] = $property.': '.$value.';';
		}

		$this->setAttribute('style', implode(' ', $style));

		return $this;
	}

}