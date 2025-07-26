# PHPDoc Meta Document

## 1. Summary

PHPDoc as a notation was first presented in 2000 by Ulf Wendel, is heavily inspired by JavaDoc,
and is currently in use by a significant percentage of public PHP projects in the field.

## 2. Why Bother?

PHPDocumentor has facilitated the growth of the PHPDoc notation, but with the growing number of other
tools that use the PHPDoc notation, it is becoming increasingly important to have a formal standard
instead of its de-facto status.

Pros:

* Developers (consumers) have a common reference to refer to when confronted with PHPDoc.
* Projects and their Developers (contributors) have an authoritative reference which they can consult.
* IDE vendors can standardize the way they use PHPDoc to aid in concerns such as auto-completion and navigation.
* Projects using the PHPDoc data to complement their functionality, such as Documentation generators or static
  analysis tools, will have a common language with their consumers.

Cons:

* If there are different uses of elements in the PHPDoc notation, then it is desirable for projects to align with this
  specification, which will cost effort to introduce.
* Given the age of the current standard and widespread adoption, it is not possible to introduce significant breaks in
  backwards compatibility with the current practices without a significant risk of alienating existing users or vendors.

## 3. Scope

### 3.1 Goals

* Provide a complete technical definition, or schema, of the PHPDoc notation.
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

 * Jaap van Otterdijk - [phpDocumentor](https://github.com/phpDocumentor/phpDocumentor2)

### 5.2 Sponsor

 * Ken Guest - [PHP-FIG](https://www.php-fig.org/)

### 5.3 Working Group members

 * Alexander Makarov - [Yii](https://github.com/yiisoft/yii2)
 * TBA

## 6. Votes

* [Entrance Vote](https://groups.google.com/forum/#!topic/php-fig/5Yd0XGd349Q)
* **Acceptance Vote**: TBD

## 7. Relevant Links

Most of the relevant links are mentioned in the PSR itself as support for individual chapters.

## 8. Past contributors

Since this document stems from the work of a lot of people in previous years, we should recognize their effort:

 * Chuck Burgess (previous Editor)
 * Mike van Riel (previous Editor)
 * Phil Sturgeon (previous sponsor)
 * Donald Gilbert (previous sponsor)
 * Gary Jones (contributor)

_**Note:** Order descending chronologically._

* [Original draft](https://github.com/phpDocumentor/phpDocumentor2/commit/0dbdbfa318d197279b414e5c0d1ffb142b31a528#docs/PSR.md)
