Module Identification
=====================

This standard declares version ranges on top of the [Module definition][].
Version ranges are used to describe dependencies. The definitions are
adapted from [Composer][].

The key words "MUST", "MUST NOT", "REQUIRED", "SHALL", "SHALL NOT", "SHOULD",
"SHOULD NOT", "RECOMMENDED", "MAY", and "OPTIONAL" in this document are to be
interpreted as described in [RFC 2119][].

[RFC 2119]: http://www.ietf.org/rfc/rfc2119.txt
[PSR-0]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-0.md
[Module definition]: https://github.com/mepeisen/fig-standards/blob/master/proposed/module-identification.md
[Composer]: http://getcomposer.org/doc/01-basic-usage.md


1. Overview
-----------

- A range without using the special characters MUST be mapped to the
  concrete version.
- The wildcard "*" can be used to match a range of versions in a short way.
- Multiple rules MUST be separated by comma.
- The operators ">=, ">", "<=", "<" MUST be used to specify a version range.


2. Concrete version
-------------------

If no special character is used the version specifies a concrete version number.

Example:
"2.5.5" means "exactly version 2.5.5"
"2.5.4-rc.3" means "exactly the third release candidate from 2.5.4"

3. Wildcards
------------

The wildcard "*" can be used to specify a version range.

Example:
"1.0.*" means "any version starting from 1.0 but below 1.1"
In technical words: "1.0.*" means ">=1.0,<1.1"

Wildcards will match versions with extra information.

Example:
"1.0.*" will match "1.0.5-rc.5"

4. Ranges
---------

A range MUST be specified by using one of the allowed operators prior the
version number.

Allowed operators are:
- ">" (greater)
- ">=" (greater equal)
- "<" (lower)
- "<=" (lower equal)

Multiple ranges are separated by comma:

Examples of valid ranges:
- >=1.0
- >=1.0, <1.1
- <1.0
- >=1.0, <1.1, >=1.2

The last example starts from version 1.0 but excludes any 1.1.* version because of known
problems. 

