# chillerlan/php-prototype-dom
[prototype.js](http://api.prototypejs.org/dom/) like DOM traversal and manipulation for PHP 7.4+.

[![PHP Version Support][php-badge]][php]
[![version][packagist-badge]][packagist]
[![license][license-badge]][license]
[![Travis][travis-badge]][travis]
[![Coverage][coverage-badge]][coverage]
[![Scrunitizer][scrutinizer-badge]][scrutinizer]
[![Packagist downloads][downloads-badge]][downloads]<br/>
[![CI][gh-action-badge]][gh-action]

[php-badge]: https://img.shields.io/packagist/php-v/chillerlan/php-prototype-dom?logo=php&color=8892BF
[php]: https://www.php.net/supported-versions.php
[packagist-badge]: https://img.shields.io/packagist/v/chillerlan/php-prototype-dom.svg?logo=packagist
[packagist]: https://packagist.org/packages/chillerlan/php-prototype-dom
[license-badge]: https://img.shields.io/github/license/chillerlan/php-prototype-dom.svg
[license]: https://github.com/chillerlan/php-prototype-dom/blob/main/LICENSE
[travis-badge]: https://img.shields.io/travis/chillerlan/php-prototype-dom/main.svg?logo=travis
[travis]: https://travis-ci.com/github/chillerlan/php-prototype-dom
[coverage-badge]: https://img.shields.io/codecov/c/github/chillerlan/php-prototype-dom.svg?logo=codecov
[coverage]: https://codecov.io/github/chillerlan/php-prototype-dom
[scrutinizer-badge]: https://img.shields.io/scrutinizer/g/chillerlan/php-prototype-dom.svg?logo=scrutinizer
[scrutinizer]: https://scrutinizer-ci.com/g/chillerlan/php-prototype-dom
[downloads-badge]: https://img.shields.io/packagist/dt/chillerlan/php-prototype-dom.svg?logo=packagist
[downloads]: https://packagist.org/packages/chillerlan/php-prototype-dom/stats
[gh-action-badge]: https://github.com/chillerlan/php-prototype-dom/workflows/CI/badge.svg
[gh-action]: https://github.com/chillerlan/php-prototype-dom/actions?query=workflow%3A%22CI%22

# Documentation

- for the extended `DOMNode` (prototypejs) methods see: [`Element.Methods`](http://api.prototypejs.org/dom/Element/Methods/)
- for the extended `DOMNodeList` methods see [`Enumerable`](http://api.prototypejs.org/language/Enumerable/)
- for the CSS selector capabilities of the several selction methods see the [`Symfony CssSelector Component`](https://symfony.com/doc/current/components/css_selector.html) documentation

## Requirements
- PHP 7.4+
  - the [`DOM`](https://www.php.net/manual/book.dom.php) and [`libXML`](https://www.php.net/manual/de/book.libxml.php) extensions

## Installation
**requires [composer](https://getcomposer.org)**

`composer.json` (note: replace `dev-main` with a [version boundary](https://getcomposer.org/doc/articles/versions.md))
```json
{
	"require": {
		"php": "^7.4 || ^8.0",
		"chillerlan/php-prototype-dom": "dev-main"
	}
}
```
Profit!

## Quickstart

```php
use chillerlan\PrototypeDOM\Document;
use chillerlan\PrototypeDOM\Node\PrototypeHTMLElement;

$document = new Document(file_get_contents('https://www.php.net/supported-versions.php'));

$supportedVersions = $document->querySelectorAll('tr.stable > td:first-of-type > a')
	->map(fn(PrototypeHTMLElement $a):string => $a->value());

var_dump($supportedVersions); // -> ['7.4', '8.0']
```
