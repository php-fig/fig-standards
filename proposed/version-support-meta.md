PSR-Version Support Meta Document
=================================

1. Summary
----------

PHP has a long history of slow adoption of new versions of the language. A major
factor in that slow adoption curve is the reluctance of public web hosts to upgrade
their offerings without pressure from their customers, while major PHP projects
are reluctant to adopt new PHP versions while few public hosts support it. Few
projects wish to be the first to drop old versions for fear of alienating their
user base. This chicken-and-egg problem is one of the reasons why many PHP projects
lag far behind PHP itself in terms of the languages features they adopt.


2. Why Bother?
--------------

"What version can we support?" is a frequent challenge for PHP projects. That
debate can eat up considerable development time, and fear of alienating users
on older PHP versions can prevent a project from adopting new language features 
that would benefit the project and its users.  That reluctance, in turn, means
there is no incentive for public hosts to offer newer PHP versions that would 
resolve that issue.

This standard draws inspiration from the GoPHP5 initiative from 2007. In that 
case, numerous projects and hosts collectively agreed on a drop-dead date for
all PHP 4 versions, and that future releases would standardize on at least PHP 5.2.
That initiative was successful in breaking the log-jam that kept PHP 4 alive well
past its retirement and delayed the implementation of PHP 5.

Rather than periodic "surge" efforts, this standard creates a rolling-GoPHP process
that both projects and hosts can build into their planning.


3. Scope
--------

## 3.1 Goals

This standard serves as a "community covenant" for projects and hosts to agree on
an approximate schedule for when they will adopt and drop support for PHP versions.
By synchronizing release schedules, no project is overly penalized for being first
to drop support for a given PHP version and public hosts have a clear timeline
they can factor into their normal upgrade schedules.

## 3.2 Non-Goals

This document does not seek to impose any architectural decisions on participating
projects, nor to dictate what language features they use.


4. Example release schedule
---------------------------

The following examples help illustrate with examples how a participating project
and host could operate with respect to PHP releases.

## 4.1 For projects

### 4.1.1 PHP minor releases.

Flubber 4.2 is the current release.  PHP 9.2.3 is the current stable release.

On 1 March 2020, PHP 9.3.0 is released.

Flubber 4.3 is released on 20 March. It is unaffected, because PHP 9.3 has been
out for less than 90 days.

Flubber 4.4 is released on 14 July. It is required to support PHP 9.3 because
it has been out for more than 90 days.

On 1 March 2021, PHP 9.4. is released.

Flubber 5.0 is released on 20 March. It is unaffected, because PHP 9.4 has been
out for less than 6 months.



## 4.2

5. Participating projects
-------------------------


6. Participating hosts
----------------------


7. People
---------

### 7.1 Editor

* Larry Garfield

### 7.2 Sponsors

* Korvin Szanto, Concrete5 (Coordinator)
* Cal Evans, Community


8. Votes
--------


9. Relevant Links
-----------------

