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
the time of writing, new functionality is very open to interpretation. PSR-N seeks
to provide a set way that both coding style tools can implement, projects can declare
adhearance to and developers can easily relate on between different projects for these
coding style reducing cognitive friction.

PSR-2 was created based upon the common practices of the PHP FIG projects at the time
but ultimately this meant it was a compromise of many of the different projects' guidelines.
The repercussions of projects changing their coding guidelines to align with PSR-2 (Almost
all projects do align with PSR-1, even if it is not explicitly stated) were seen to be too
great (losing git history, huge changesets and breaking existing patches/pull requests) and
this therefore hurt adoption of PSR-2. Therefore, whilst PSR-N will be more prescriptive
than descriptive of the projects, but this is in the hope that it will mean that projects
are more likely to adhere to it as it becomes a case of following the guide when code is
initally written as opposed to changing large amounts of existing code.

However it is for a lack of wanting to be dictaorial that we will aim to apply PSR-2
styling, rationale and stances (Described in Section 4, Approaches) in PSR-N instead of
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

This PSR is an extension of PSR-2, and therefore also an extension of PSR-1. It shall be
included in the document that compliance with PSR-N implictly requires compliance with
PSR-2 and PSR-1.

This PSR will include coding style guidelines related to new functionality added to PHP
after the publication of PSR-2; this includes PHP 5.5, PHP 5.6 and PHP 7.0.

This PSR will also include clarifications on the text of PSR-2, as described in the
PSR-2 Errata.

## 3.2 Non-Goals

It is not the intention of this PSR to add entirely new coding style guidelines that
were available to be considered for inclusion in PSR-1 or PSR-2.

PSR-N will also not change anything stipulated in PSR-1 and PSR-2.

4. Approaches
-------------

The overarching approach is to attempt to apply existing PSR-2 styling and rationale to
new functionality as opposed to establishing a new conventions.


5. People
---------

### 5.1 Editor(s)

* Michael Cullum

### 5.2 Sponsors

* Korvin Szanto - concrete5 (Coordinator)
* Alexander Makarov - Yii Framework

6. Votes
--------

* **Entrance Vote: ** http://groups.google.com...

7. Relevant Links
-----------------

_**Note:** Order descending chronologically._

* [Inspiration Mailing List Thread](https://groups.google.com/forum/?utm_medium=email&utm_source=footer#!topic/php-fig/wh9avopSR9k)
* [Inital Mailing List PSR Proposal Thread](https://groups.google.com/forum/?utm_medium=email&utm_source=footer#!topic/php-fig/MkFacLdfGso)
