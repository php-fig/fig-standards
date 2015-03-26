Security Disclosure Meta Document
=================================

1. Summary
----------

Unfortunately with all software development, security vulnerabilities are a
fact of life that need to be addressed. It is important that when security
vulnerabilities are found that researchers have an easy channel to the
projects in question allowing them to disclose the issue to a controlled
group of people.


2. Why Bother?
--------------

As of right now, there isn't really a common standard for most parts of this
process. There isn't a standard where researchers can find out about the
process for handling security issues for any given project. There is also
no standard that explains to researchers what they can expect to happen if
they report a vulnerability.

3. Scope
--------

## 3.1 Goals

* A defined process for how vulnerabilities are reported, how these get fixed
  and finally disclosed to the public

## 3.2 Non-Goals

* Methods for reducing security vulnerabilities
* Publication of security issues and fixes

4. Approaches
-------------

Currently the most viable approach seems to be defining a base line workflow
for how security vulnerabilities go from discovery to fixing to public
disclosure. Inspiration could be drawn from [1].

For further reference here is a list of security disclosure processes in
various PHP and non-PHP projects:

* http://symfony.com/doc/current/contributing/code/security.html
* http://framework.zend.com/security/
* http://www.yiiframework.com/security/
* https://www.drupal.org/security
* http://codex.wordpress.org/FAQ_Security
* http://www.sugarcrm.com/page/sugarcrm-security-policy/en
* http://typo3.org/teams/security/
* http://cakephp.org/development
* http://www.concrete5.org/developers/security/
* http://developer.joomla.org/security.html
* http://wiki.horde.org/SecurityManagement
* http://www.revive-adserver.com/support/bugs/
* http://magento.com/security
* http://www.apache.org/security/committers.html
* https://www.mozilla.org/en-US/about/governance/policies/security-group/bugs/
* http://www.openbsd.org/security.html

A summary of the differences and similarities can be found here:
https://groups.google.com/d/msg/php-fig-psr-9-discussion/puGV_X0bj_M/Jr_IAS40StsJ

5. People
---------

### 5.1 Editor

* Lukas Kahwe Smith

### 5.2 Sponsors

* Larry Garfield (Drupal)
* Korvin Szanto (concrete5)

### 5.3 Coordinator

* Korvin Szanto (concrete5)

6. Votes
--------


7. Relevant Links
-----------------

[1]: http://symfony.com/doc/current/contributing/code/security.html