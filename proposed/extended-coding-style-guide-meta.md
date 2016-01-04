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
the time of writing, new functionality is very open to interpretation. PSR-12 seeks
to provide a set way that both coding style tools can implement, projects can declare
adherence to and developers can easily relate on between different projects for these
coding style reducing cognitive friction.

PSR-2 was created based upon the common practices of the PHP FIG projects at the time
but ultimately this meant it was a compromise of many of the different projects' guidelines.
The repercussions of projects changing their coding guidelines to align with PSR-2 (Almost
all projects do align with PSR-1, even if it is not explicitly stated) were seen to be too
great (losing git history, huge changesets and breaking existing patches/pull requests).

PSR-2 required adopters to reformat large amounts of existing code which stifled adoption.
To help alleviate this issue with PSR-12, we have taken a more prescriptive approach and
defined the standards for new language features as they are released. We hope that because
this specification is defined prior to mass amounts of code being written, it will have a
better chance of being adopted but this is in the hope that it will mean that projects.

However it is for a lack of wanting to be dictatorial that we will aim to apply PSR-2
styling, rationale and stances (Described in Section 4, Approaches) in PSR-12 instead of
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

This PSR is an extension of PSR-2, and therefore also an extension of PSR-1. The basis of
PSR-12 is PSR-2 and therefore a list of differences is provided below to assist with migration
but it should be considered as an independent specification.

This PSR will include coding style guidelines related to new functionality added to PHP
after the publication of PSR-2; this includes PHP 5.5, PHP 5.6 and PHP 7.0. This PSR will
also include clarifications on the text of PSR-2, as described in the PSR-2 Errata.

## 3.2 Non-Goals

It is not the intention of this PSR to add entirely new coding style guidelines PSR-12 will
also not change anything stipulated in PSR-1 and PSR-2.

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
and the current implementation was chosen due to consistency with other parts of the PSR-12
specification that came from PSR-2.

5. Changelog from PSR-2
------------------------

Please note this changelog is not a verbose list of changes from PSR-2 but highlights the most
notable changes. It should be considered a new specification and therefore you should read the
specification for a full understanding of its contents.

### New Statements

* Lowercase for all keywords - Section 2.5
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
