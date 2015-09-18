## Introduction

PHP has a long history of slow adoption of new versions of the language. A major
factor in that slow adoption curve is the reluctance of public web hosts to upgrade
their offerings without pressure from their customers, while major PHP projects
are reluctant to adopt new PHP versions while few public hosts support it. Few
projects wish to be the first to drop old versions for fear of alienating their
user base. This chicken-and-egg problem is one of the reasons why many PHP projects
lag far behind PHP itself in terms of the languages features they adopt.

The key words "MUST", "MUST NOT", "REQUIRED", "SHALL", "SHALL NOT", "SHOULD",
"SHOULD NOT", "RECOMMENDED", "MAY", and "OPTIONAL" in this document are to be
interpreted as described in [RFC 2119][].

[RFC 2119]: http://tools.ietf.org/html/rfc2119

## Goal

This standard serves as a "community covenant" for projects and hosts to agree on
an approximate schedule for when they will adopt and drop support for PHP versions.
By synchronizing release schedules, no project is overly penalized for being first
to drop support for a given PHP version and public hosts have a clear timeline
they can factor into their normal upgrade schedules.

## Definitions

* **Major version** - A major version of a project is a release that is documented
as containing API breaks from a previous release.  Typically a major version 
also contains new features or functionality but that is not required.

* **Minor version** - A minor version of a project is a release that is documented
as containing new features or functionality but not API breaks.  Typically a minor 
version also contains bug fixes but that is not required.

* **Patch version** - A patch version of a project is a release that is documented
as containing bug fixes but no new features or API changes.

* **Support** - A given release of a project *supports* a given PHP release if
it executes without parse errors and performs its designated task as expected.
It is not required to leverage any particular language feature or syntax, only
that the code executes without error.

* **Not Support** - A given release of a project that *does not support* a given
PHP release if no effort is make to ensure that it executes without parse errors
or performs as expected.  While the code may execute correctly, that is considered
to be incidental.  A project SHOULD NOT attempt to break compatibility with a given
PHP version simply for its own sake; it should only not consider that PHP version
either way when determining what language features to use.

* **Offer** - A public web host *offers* a PHP version if its customers are able
to host their application using that PHP version on all hosting plans that offer
PHP, regardless of cost.  A web host MAY require a user to select a non-default
option in a control panel or similar, but MUST NOT require a custom configuration
or other special arrangements with the host.

