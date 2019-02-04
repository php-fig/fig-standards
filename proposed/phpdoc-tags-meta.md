# PHPDoc Tags Meta Document

## 1. Summary

The purpose of this PSR is to document (in a catalog style) the de facto list of tags historically in use
in the PHP community.

## 2. Why Bother?

We wish to properly standardize the de facto usage of tags as code documentation.

## 3. Scope

### 3.1 Goals

* Provide a complete technical definition, or schema, of the common tags in PHPDoc notation.
* Introduce new concepts matching best practices or design patterns in use today.

### 3.2 Non-Goals

* This PSR does not provide a recommendation on how and when to use the concepts described in this document, so it is
  not a coding standard.

## 4. Approaches

### 4.1 Chosen Approach

We have decided to formalize the existing practices, observe non-documented usages (such as Doctrine-style
annotations), and observe feature requests with Documentation generators (such as phpDocumentor).

The combination of these should be described in sufficient detail as to reduce the amount of possible interpretation.

In addition to the above, the authors have taken care to provide for future expansions and tag additions that do not
affect the Syntax of PHPDoc itself.

Pros:

* Delivers a machine-parsable and verifiable specification.
* Well-rounded proposal due to the number of factors considered.

Cons:

* Technical and verbose.
* Can only be extended when the syntax is not affected.

## 5. People

### 5.1 Editor

 * Chuck Burgess - [PEAR](https://pear.php.net)

### 5.2 Sponsor

 * Michael Cullum

### 5.3 Working group members

 * Alexey Gopachenko - [PhpStorm](https://www.jetbrains.com/phpstorm)
 * Matthew Brown - [Psalm](https://github.com/vimeo/psalm)
 * Jan Tvrdík - [PHPStan](https://github.com/phpstan/phpstan)
 * Jaap van Otterdijk - [phpDocumentor](https://github.com/phpDocumentor/phpDocumentor2)

## 6. Votes

* [Entrance Vote](https://groups.google.com/forum/#!topic/php-fig/5Yd0XGd349Q)
* **Acceptance Vote**: TBD

## 7. Relevant Links

Most of the relevant links are mentioned in the PSR itself as support for individual chapters.

_**Note:** Order descending chronologically._

* [Original draft](https://github.com/phpDocumentor/phpDocumentor2/commit/0dbdbfa318d197279b414e5c0d1ffb142b31a528#docs/PSR.md)
