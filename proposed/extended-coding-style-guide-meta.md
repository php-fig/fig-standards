Extended Coding Style Guide Meta Document
=========================================

1. Summary
----------

This document describes the process and discussions that led to the Extended Coding
Style PSR. Its goal is to explain the reasons behind each decision.

2. Why Bother?
--------------

PSR-2 was accepted in 2012 and since then a number of changes have been made to PHP,
most notably recent changes for PHP 7, which have implications for coding style
guidelines. Whilst PSR-2 is very comprehensive of PHP functionality that existed at
the time of writing, new functionality is very open to interpretation. PSR__x__2 seeks
to provide a set way that both coding style tools can implement, projects can declare
adherence to and developers can easily relate on between different projects for these
coding style reducing cognitive friction.

PSR-2 was created based upon the common practices of the PHP FIG projects at the time
but ultimately this meant it was a compromise of many of the different projects' guidelines.
The repercussions of projects changing their coding guidelines to align with PSR-2 (Almost
all projects do align with PSR__x__, even if it is not explicitly stated) were seen to be too
great (losing git history, huge changesets and breaking existing patches/pull requests).

PSR-2 required adopters to reformat large amounts of existing code which stifled adoption.
To help alleviate this issue with PSR__x__2, we have taken a more prescriptive approach and
defined the standards for new language features as they are released. We hope that because
this specification is defined prior to mass amounts of code being written, it will have a
better chance of being adopted but this is in the hope that it will mean that projects.

However it is for a lack of wanting to be dictatorial that we will aim to apply PSR-2
styling, rationale and stances (Described in Section 4, Approaches) in PSR__x__2 instead of
establishing new conventions.

3. Scope
--------

## 3.1 Goals

This PSR shares the same goals as PSR-2.

> The intent of this guide is to reduce cognitive friction when scanning code from
> different authors. It does so by enumerating a shared set of rules and expectations
> about how to format PHP code.
> When various authors collaborate across multiple projects, it helps to have one set
> of guidelines to be used among all those projects. Thus, the benefit of this guide is
> not in the rules themselves, but in the sharing of those rules.

This PSR is an extension of PSR-2, and therefore also an extension of PSR__x__. The basis of
PSR__x__2 is PSR-2 and therefore a list of differences is provided below to assist with migration
but it should be considered as an independent specification.

This PSR will include coding style guidelines related to new functionality added to PHP
after the publication of PSR-2; this includes PHP 5.5, PHP 5.6 and PHP 7.0. This PSR will
also include clarifications on the text of PSR-2, as described in the PSR-2 Errata.

## 3.2 Non-Goals

It is not the intention of this PSR to add entirely new coding style guidelines PSR__x__2 will
also not change anything stipulated in PSR__x__ and PSR-2.

4. Approaches
-------------

The overarching approach is to attempt to apply existing PSR-2 styling and rationale to
new functionality as opposed to establishing new conventions.

### Strict Types Declarations

There was a discussion about whether or not strict types should be enforced in the standard
https://github.com/cs-extended/fig-standards/issues/7. All were in agreement we should only
use a MUST or MUST NOT statement and avoid the use of a SHOULD statement and nobody wanted
to say that strict types could not be declared. The discussion was whether it should be
considered a coding style item which should be covered or whether it was out of scope and it
was decided to be out of scope of a coding style guide.

## Finally and Return Types Declaration Spacing

Numerous different options were suggested and they can be seen
[here for return type declarations](https://gist.github.com/michaelcullum/c025f3870c9ea1dd2668#file-returntypesspacing-php) or
[here for finally blocks](https://gist.github.com/michaelcullum/c025f3870c9ea1dd2668#file-finallyblocks-php)
and the current implementation was chosen due to consistency with other parts of the PSR__x__2
specification that came from PSR-2.

## Enforcing short form for all type keywords

PHP 7.0 introduced [scalar types declaration](http://php.net/manual/en/functions.arguments.php#functions.arguments.type-declaration)
which does not support long type aliases. Therefore it makes sense to enforce primary short type forms to be used to
have uniform syntax and prevent possible confusion.



## Public Survey

In order to settle things using data, survey was conducted and responses from 142 people 
including 17 project representatives were gathered:

### Fig Representative Results

| Representative          | Project           | Compound namespaces with a depth of two or more MUST not be used | Header statement grouping and ordering | Declare statements must each be on their own line | Declare statements in PHP files containing markup | Declare statements have no spaces: `declare(strict_types=1);` | Block declare statement formatting | `new` keyword usage, parenthesis required |Return type declaration formatting |Use statement leading slashes disallowed | Block namespace declaration formatting | General operator spacing |Try, Catch, Finally formatting | Anonymous class declaration formatting | Keyword casing, only lower case | Type keywords, short form only |
| --------------          | -------           | ---------------------------------------------------- | ---------------------------------- | ----------------------------------------- | ------------------------------------------- | -------------------------------------------------------- | ------------------------------- | ------------------------------------- |------------------------------- |------------------------------------ | ----------------------------------- | ---------------------- |--------------------------- | ----------------------------------- | --------------------------- | -------------------------- |
| Alexander Makarov       |  Yii framework    | +1 | +1 | +1 | __x__ | +1 | +1 | +1 | +1 | +1 | +1 | +1 | +1 | +1 | +1 | +1 |
| Korvin Szanto           | concrete5         | +1 | +1 | +1 | +1 | +1 | +1 | +1 | +1 | +1 | +1 | +1 | +1 | +1 | +1 | +1 |
| Leo Feyer               | Contao            | +1 | +1 | +1 | +1 | +1 | +1 | +1 | +1 | +1 | +1 | +1 | +1 | +1 | +1 | +1 |
| Larry Garfield          | Drupal            | +1 | +1 | +1 | +1 | +1 | __x__ | +1 | +1 | +1 | __x__ | +1 | +1 | __x__ | +1 | +1 |
| André R.                | eZ                | +1 | +1 | +1 | +1 | +1 | +1 | +1 | +1 | +1 | +1 | +1 | +1 | +1 | +1 | +1 |
| Jan Schneider           | Horde             | +1 | +1 | +1 | +1 | +1 | +1 | +1 | +1 | +1 | +1 | +1 | +1 | +1 | +1 | +1 |
| Karsten Dambekalns      | Neos and Flow     | +1 | +1 | +1 | +1 | __x__ | +1 | +1 | +1 | +1 | +1 | +1 | +1 | +1 | +1 | +1 |
| Andres Gutierrez        | Phalcon           | __x__ | +1 | +1 | +1 | +1 | +1 | +1 | +1 | +1 | +1 | +1 | +1 | +1 | +1 | +1 |
| Ryan Thompson           | PyroCMS           | +1 | +1 | +1 | +1 | +1 | +1 | +1 | +1 | __x__ | __x__ | +1 | +1 | +1 | +1 | +1 |
| Matteo Beccati          | Revive Adserver   | +1 | +1 | +1 | +1 | +1 | +1 | +1 | +1 | +1 | +1 | __x__ | +1 | +1 | +1 | +1 |
| Damian Mooyman          | SilverStripe      | +1 | +1 | +1 | +1 | +1 | +1 | +1 | +1 | +1 | +1 | +1 | +1 | +1 | +1 | +1 |
| Brian Retterer          | Stormpath PHP SDK | +1 | +1 | +1 | __x__ | __x__ | +1 | __x__ | +1 | __x__ | +1 | +1 | +1 | +1 | __x__ | __x__ |
| Matthew Weier O'Phinney | Zend Framework    | __x__ | +1 | +1 | __x__ | +1 | +1 | +1 | __x__ | +1 | +1 | +1 | +1 | +1 | +1 | +1 |
| Jordi Boggiano          | Composer          | __x__ | __x__ | __x__ | +1 | +1 | +1 | __x__ | +1 | +1 | +1 | +1 | +1 | +1 | +1 | +1 |
| Ben Marks               | Magento           | +1 | +1 | +1 | +1 | +1 | +1 | +1 | +1 | +1 | +1 | +1 | +1 | +1 | +1 | +1 |
| Chuck Burgess           | PEAR              | +1 | +1 | +1 | +1 | +1 | +1 | +1 | +1 | +1 | +1 | +1 | +1 | +1 | +1 | +1 |
|                         | **Totals**:       |13/3|15/1|15/1|13/3|14/2|15/1|14/2|15/1|14/2|14/2|15/1|16/0|15/1|15/1|15/1|

### General non-representative voters

| Question | For | Against | Percentage For |
| -------- | --- | ------- | -------------- |
| Compound namespaces required depth | 114 | 12 | 89.47% | 
| Header statement grouping and ordering | 113 | 13 | 88.5% |
| Declare statements must each be on their own line | 120 | 6 | 95% |
| Declare statements in PHP files containing markup | 119 | 7 | 94.12% |
| Declare statements have no spaces | 116 | 10 | 91.38% |
| Block declare statement formatting | 118 | 8 | 93.22% |
| `new` keyword usage, parenthesis required | 116 | 10 | 91.38% |
| Return type declaration formatting | 115 | 11 | 90.43% |
| Use statement leading slashes disallowed | 118 | 8 | 93.22% |
| Block namespace declaration formatting | 120 | 6 | 95% |
| General operator spacing | 123 | 3 | 97.56% |
| Try, Catch, Finally formatting | 124 | 2 | 98.39% |
| Anonymous class declaration formatting | 117 | 9 | 92.31% |
| Keyword casing, only lower case | 124 | 2 | 98.39% |
| Type keywords, short form only | 121 | 5 | 95.87% |


5. Changelog from PSR-2
------------------------

Please note this changelog is not a verbose list of changes from PSR-2 but highlights the most
notable changes. It should be considered a new specification and therefore you should read the
specification for a full understanding of its contents.

### New Statements

* Lowercase for all keywords - Section 2.5
* Short form for all type keywords - Section 2.5
* Use statement grouping - Section 3
* Use statement blocks - Section 3
* Declare statement/Strict types declaration usage - Section 3
* Parentheses are always required for class instantiation - Section 4
* Return type declarations - Section 4.5
* Type hints - Section 4.5
* Add finally block - Section 5.6
* Operators - Section 6
* Anonymous classes - Section 8

### Clarifications and Errata
* Adjust 'methods' to 'methods and functions' in a number of instances - Throughout
* Adjust references to classes and interfaces to also include traits - Throughout
* StudlyCaps meaning clarified as PascalCase - Section 2.1
* The last line should not be blank but contain an EOL character - Section 2.2
* Blank lines may be added for readability except where explicitly forbidden within the PSR - Section 2.3
* PSR-2 errata statement about multi-line arguments - Section 4
* PSR-2 errata statement about extending multiple interfaces - Section 4
* Forbid blank lines before/after closing/opening braces for classes - Section 4

6. People
---------

### 5.1 Editor(s)

* Korvin Szanto (Former coordinator)

### 5.2 Sponsors

* Alexander Makarov - Yii Framework (Coordinator)
* Robert Deutz - Joomla

### 5.3 Contributors
* Michael Cullum (Former Editor)

7. Votes
--------

* **Entrance Vote: ** https://groups.google.com/forum/?utm_medium=email&utm_source=footer#!msg/php-fig/P9atZLOcUBM/_jwkvlYKEAAJ

8. Relevant Links
-----------------

_**Note:** Order descending chronologically._

* [Inspiration Mailing List Thread](https://groups.google.com/forum/?utm_medium=email&utm_source=footer#!topic/php-fig/wh9avopSR9k)
* [Initial Mailing List PSR Proposal Thread](https://groups.google.com/forum/?utm_medium=email&utm_source=footer#!topic/php-fig/MkFacLdfGso)
