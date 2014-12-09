Security Disclosure Meta Document
=================================

1. Summary
----------

Unfortunately with all software development, security vulnerabilities are a
fact of life that need to be addressed. It is important that when security
vulnerabilities are found that researchers have an easy channel to the
projects in question allowing them to disclose the issue to a controlled
group of people. Furthermore the process how a reported issues is then
solved and the solution and information provided to the general public must
be clear to all parties involved: the researcher, the project leads and
the user base. Especially today where PHP developers are sharing code across
projects more than ever, it also adds another dimension: how to deal with
upstream projects to ensure they have sufficient time to prepare themselves.


2. Why Bother?
--------------

As of right now, there isn't really a common standard for most parts of this
process. There isn't a standard where researchers can find out about the
process for handling security issues for any given project. There is also
no standard that explains to researchers what they can expect to happen if
they report a vulnerability.

End users will want to ensure that they stay informed about security issues.
However they also want to be able to quickly check if they are affected to be
able to take the necessary steps.

Upstream users of code will also want to know these details. Furthermore they
will want to know if its possible for them to be included into possible closed
discussions before details about a security issue are made public.

3. Scope
--------

## 3.1 Goals

* A defined process for how vulnerabilities are reported, how these get fixed
  and finally how they get disclosed to the public
* Means to help in (semi-)automating discovery and fixing of known security
  issues in projects using the affected code

## 3.2 Non-Goals

* Methods for reducing security vulnerabilities

4. Approaches
-------------

Currently the most viable approach seems to be defining a base line workflow
for how security vulnerabilities go from discovery to fixing to public
disclosure. Inspiration could be drawn from [1].

One key aspect here is that the information flow should be as
structured as possible to help in automating things as much possible.
For example, vulnerabilities should be published in a defined location
and in a defined format. Inspiration could be taken from [2].

That being said, the standard should not rely on any central authority
above the projects. This is to ensure that no project becomes depend on an
outside authority for something as sensitive as security related topics.
However due to defined locations and formats, it will become possible for
other people to build centralized tools around this information.

5. People
---------

### 5.1 Editor

* Lukas Kahwe Smith

### 5.2 Sponsors

* Larry Garfeild (Drupal)
* Korvin Szanto (concrete5)

### 5.3 Coordinator

* Korvin Szanto (concrete5)

6. Votes
--------


7. Relevant Links
-----------------

[1]: http://symfony.com/doc/current/contributing/code/security.html
[2]: https://github.com/FriendsOfPHP/security-advisories

Initial discussion: https://groups.google.com/d/msg/php-fig/45AIj5bPHJ4/ThERB43j-u8J
