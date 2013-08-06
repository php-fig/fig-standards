PHPDoc Meta Document
====================

1. Summary
----------

The purpose of documentation using PHPDoc is to provide a comprehensive but flexible way to describe a software system
at the smallest possible level of detail. This type of documentation aids contributors and consumers of your source
code to, for example, understand what type of information needs to be passed to specific methods, or how to be able to
consume a class of the project that a consumer want to use.

By documenting specific elements inside the source code the documentation for that part of the source code will be less
susceptible to becoming out of date.

PHPDoc as a notation has existed for more than ten years now, is heavily inspired by JavaDoc, and is currently in use by a
significant percentage of public PHP projects in the field.

2. Why Bother?
--------------

PHPDocumentor has spearheaded and facilitated the growth of the PHPDoc notation, but with the growing number of other
tools that use the PHPDoc notation, it is becoming increasingly important to have an official and formal standard
instead of the de-facto status that is currently provided.

An additional goal for this PSR is to deprecate obsolete elements and introduce new concepts and syntaxes to reflect the
current status of the PHP language, and to facilitate best practices and design patterns in use today and in the
foreseeable future.

Pros:

* Developers (consumers) have a common reference to refer to when confronted with PHPDoc.
* Projects and their Developers (contributors) have an authoritative reference which they can consult.
* IDE vendors can standardize the way they use PHPDoc to aid in concerns such as auto-completion and navigation.
* Projects using the PHPDoc data to complement their functionality, such as Documentation generators or applications
  using annotations, will have a common language with their consumers.
* Missing functionality can be described and implemented by aforementioned stakeholders.

Cons:

* If there are different uses of elements in the PHPDoc notation, then it is desirable for projects to align with this
  specification, which will cost effort to introduce.
* Deprecation of well-known PHPDoc elements may lead to a period of confusion or resistance to the proposed changes. It
  is for this reason that concepts are deprecated and not removed.
* Given the age of the current standard and widespread adoption, it is not possible to introduce significant breaks in
  backwards compatibility with the current practices without a significant risk of alienating existing users or vendors.

3. Scope
--------

## 3.1 Goals

* Provide a complete technical definition, or schema, of the PHPDoc notation.
* Introduce new concepts matching best practices or design patterns in use today and in the foreseeable future.
* Deprecate old concepts that are replaced by newer concepts or are no longer in use in today's PHP landscape.

## 3.2 Non-Goals

* This PSR does not provide a recommendation on how and when to use the concepts described in this document,
  so it is not a coding standard.
* This PSR facilitates the creation of annotations by allowing the notation needed for Symfony/Doctrine style
  annotations, but does not describe a style of annotations or which "defined annotations" exist in use. The concept of annotations is only
  alluded to and is out of scope for this PSR.

4. Approaches
-------------

### 4.1 Chosen Approach

We have decided to formalize the existing practices, observe non-documented usages (such as Doctrine-style
annotations), and observe feature requests with Documentation generators (such as phpDocumentor).

The combination of these should be described in sufficient detail as to reduce the amount of possible interpretation.

In addition to the above, the authors have taken care to provide for future expansions and tag additions that do not
affect the Syntax of PHPDoc itself.

Pros:

* Delivers a machine-parsable and verifyable specification.
* Well-rounded proposal due to the number of factors considered.

Cons:

* Technical and verbose.
* Can only be extended when the syntax is not affected.

5. People
---------

### 5.1 Editor(s)

* Mike van Riel

### 5.2 Sponsors

* Phil Sturgeon
* Donald Gilbert

### 5.3 Contributors

* Chuck Burgess
* Gary Jones

6. Votes
--------

* **Entrance Vote: ** TBD
* **Acceptance Vote:** TBD

7. Relevant Links
-----------------

Most of the relevant links are mentioned in the PSR itself as support for individual chapters.

_**Note:** Order descending chronologically._

* [Original draft](https://github.com/phpDocumentor/phpDocumentor2/commit/0dbdbfa318d197279b414e5c0d1ffb142b31a528#docs/PSR.md)
