Security Disclosure Meta Document
=================================

1. Summary
----------

There are two aspects with dealing with security issues: One is the process
by which security issues are reported and fixed in projects, the other
is how the general public is informed about the issues and any remedies
available. While PSR-9 addresses the former, this PSR, ie. PSR-10, deals with
the later. So the goal of PSR-10 is to define how security issues are disclosed
to the public and what format such disclosures should follow. Especially today
where PHP developers are sharing code across projects more than ever, this PSR
aims to ease the challenges in keeping an overview of security issues in all
dependencies and the steps required to address them.

2. Why Bother?
--------------

End users  want to ensure that they stay informed about security issues.
However they also want to be able to quickly check if they are affected to be
able to take the necessary steps.

Upstream users of code will also want to know these details. Furthermore they
will want to know if its possible for them to be included into possible closed
discussions before details about a security issue are made public.

3. Scope
--------

## 3.1 Goals

* Means to help in (semi-)automating discovery and fixing of known security
  issues in projects using the affected code

## 3.2 Non-Goals

* Process for how vulnerabilities are reported and fixed
* Methods for reducing security vulnerabilities

4. Approaches
-------------

A key aspect here is that the information flow should be as structured as
possible to enable as much automation as possible. For example,
vulnerabilities should be published in a defined location and in a defined
format. Inspiration could be taken from [1].

That being said, the standard should not rely on any central authority
above the projects. This is to ensure that no project becomes depend on an
outside authority for something as sensitive as security related topics.
However due to defined locations and formats, it will become possible for
other people to build centralized tools around this information.

5. People
---------

### 5.1 Editor

* Michael Hess

### 5.2 Sponsors

* Larry Garfield (Drupal)
* Korvin Szanto (concrete5)

### 5.3 Coordinator

* Korvin Szanto (concrete5)

### 5.4 Contributors

* Lukas Kahwe Smith

6. Votes
--------

7. Relevant Links
-----------------

[1]: https://github.com/FriendsOfPHP/security-advisories

Initial discussion: https://groups.google.com/d/msg/php-fig/45AIj5bPHJ4/ThERB43j-u8J
Discussion: https://groups.google.com/forum/#!forum/php-fig-psr-9-discussion
