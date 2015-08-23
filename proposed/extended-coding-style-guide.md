Extended Coding Style Guide
===========================

The key words "MUST", "MUST NOT", "REQUIRED", "SHALL", "SHALL NOT", "SHOULD",
"SHOULD NOT", "RECOMMENDED", "MAY", and "OPTIONAL" in this document are to be
interpreted as described in [RFC 2119][].

[RFC 2119]: http://tools.ietf.org/html/rfc2119

1. Overview
-----------

This guide extends and expands on [PSR-2][], the coding style guide and
[PSR-1][], the basic coding standard.

Like [PSR-2][], the intent of this guide is to reduce cognitive friction when scanning
code from different authors. It does so by enumerating a shared set of rules and
expecations about how to format PHP code. This PSR seeks to provide a set way that
coding style tools can implement, projects can declare ahearance to and developers
can easily relate to between different projects. When various authors collaborate
across multiple projects, it helps to have one set of guidelines to be used among
all those rpojects. Thus, the benefit of this guide is not in the rules themselves
but the sharing of those rules.

[PSR-2][] was accepted in 2012 and since then a number of changes have been made to PHP
which have implications for coding style guidelines. Whilst [PSR-2] is very comprehensive
of php functionality that existed at the time of writing, new functionality is very
open to interpretation. This PSR therefore seeks to clarify the content of PSR-2 in
a more modern context with new functionality available, and make the errata to PSR-2
binding.

2. Specification
----------------

1. Code MUST adhere to a "basic coding standard" PSR, [PSR-1][]
1. Code MUST adhere to the "coding style guide" PSR, [PSR-2][]

[PSR-2]: http://www.php-fig.org/psr/psr-2/
[PSR-1]: http://www.php-fig.org/psr/psr-1/
