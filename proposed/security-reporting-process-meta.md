Security Disclosure Meta Document
=================================

1. Summary
----------

There are two aspects with dealing with security issues: One is the process
by which security issues are reported and fixed in projects, the other
is how the general public is informed about the issues and any remedies
available. While PSR-10 addresses the later, this PSR, ie. PSR-9, deals with
the former. So the goal of PSR-9 is to define the process by which security
researchers and report security vulnerabilities to projects. It is important
that when security vulnerabilities are found that researchers have an easy
channel to the projects in question allowing them to disclose the issue to a
controlled group of people.

2. Why Bother?
--------------

As of right now, there isn't a common standard for most parts of this process.
That is there isn't a standard where researchers can find out about the
process for handling security issues for any given project. There is also
no standard that explains to researchers what they can expect to happen if
they report a vulnerability. More importantly there is no standard on which
projects can base the security reporting process that best fits them.

3. Scope
--------

## 3.1 Goals

* A defined process for how vulnerabilities are reported, the process by which
  these get fixed and finally disclosed to the public

## 3.2 Non-Goals

* Methods for reducing security vulnerabilities
* Publication of security issues and fixes (see PSR-10)

4. Approaches
-------------

Currently the most viable approach seems to be defining a base line workflow
for how security vulnerabilities go from discovery to fixing to public
disclosure. Inspiration could be drawn from this list of security disclosure
processes in various PHP and non-PHP projects:

* https://symfony.com/security
* https://framework.zend.com/security
* https://www.yiiframework.com/security
* https://www.drupal.org/security
* https://codex.wordpress.org/FAQ_Security
* https://www.sugarcrm.com/legal/security-policy
* https://typo3.org/teams/security/
* https://book.cakephp.org/3.0/en/contributing/tickets.html#reporting-security-issues
* https://www.concrete5.org/developers/security/
* https://developer.joomla.org/security.html
* https://wiki.horde.org/SecurityManagement
* https://www.revive-adserver.com/support/bugs/
* https://magento.com/security
* https://www.apache.org/security/committers.html
* https://www.mozilla.org/en-US/about/governance/policies/security-group/bugs/
* https://www.openbsd.org/security.html

A summary of the differences and similarities can be found here:
https://groups.google.com/d/msg/php-fig-psr-9-discussion/puGV_X0bj_M/Jr_IAS40StsJ

5. People
---------

### 5.1 Editor

* Michael Hess

### 5.2 Sponsors

* Larry Garfield (Drupal)
* Korvin Szanto (concrete5)

### 5.3 Coordinator

* Larry Garfield (Drupal)

### 5.4 Contributors

* Lukas Kahwe Smith

6. Votes
--------

7. Relevant Links
-----------------
