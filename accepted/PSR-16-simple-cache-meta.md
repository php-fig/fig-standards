# PSR-16 Meta Document

## 1. Summary

Caching is a common way to improve the performance of any project, and many
libraries make use or could make use of it. Interoperability at this level
means libraries can drop their own caching implementations and easily rely
on the one given to them by the framework, or another dedicated cache
library the user picked.

## 2. Why Bother?

PSR-6 solves this problem already, but in a rather formal and verbose way for
what the most simple use cases need. This simpler approach aims to build a
standardized layer of simplicity on top of the existing PSR-6 interfaces.

## 3. Scope

### 3.1 Goals

* A simple interface for cache operations.
* Basic support for operations on multiple keys for performance (round-trip-time)
  reasons.
* Providing an adapter class that turns a PSR-6 implementation into a
  PSR-Simple-Cache one.
* It should be possible to expose both caching PSRs from a caching library.

### 3.2 Non-Goals

* Solving all possible edge cases, PSR-6 does this well already.

## 4. Approaches

The approach chosen here is very barebones by design, as it is to be used
only by the most simple cases. It does not have to be implementable by all
possible cache backends, nor be usable for all usages. It is merely a layer
of convenience on top of PSR-6.

## 5. People

### 5.1 Editor(s)

* Paul Dragoonis (@dragoonis)

### 5.2 Sponsors

* Jordi Boggiano (@seldaek) - Composer (Coordinator)
* Fabien Potencier (@fabpot) - Symfony

### 5.3 Contributors

For their role in the writing the initial version of this cache PSR:

* Evert Pot (@evert)
* Florin Pățan (@dlsniper)

For being an early reviewer

* Daniel Messenger (@dannym87)

## 6. Votes

* **Entrance Vote:**  https://groups.google.com/d/topic/php-fig/vyQTKHS6pJ8/discussion
* **Acceptance Vote:**  https://groups.google.com/d/msg/php-fig/A8e6GvDRGIk/HQBJGEhbDQAJ

## 7. Relevant Links

* [Survey of existing cache implementations][1], by @dragoonis

[1]: https://docs.google.com/spreadsheet/ccc?key=0Ak2JdGialLildEM2UjlOdnA4ekg3R1Bfeng5eGlZc1E#gid=0

## 8. Errata
### 8.1 Throwable

The 2.0 release of the `psr/simple-cache` package updates `Psr\SimpleCache\CacheException` to extend `\Throwable`.  This is considered a backwards compatible change for implementing libraries as of PHP 7.4.

### 8.2 Type additions

The 2.0 release of the `psr/simple-cache` package includes scalar parameter types and increases the minimum PHP version to 8.0.  This is considered a backwards compatible change for implementing libraries as PHP 7.2 introduces covariance for parameters.  Any implementation of 1.0 is compatible with 2.0. For calling libraries, however, this reduces the types that they may pass (as previously any parameter that could be cast to string could be accepted) and as such requires incrementing the major version.

The 3.0 release includes return types.  Return types break backwards compatibility for implementing libraries as PHP does not support return type widening.

Implementing libraries **MAY** add return types to their own packages at their discretion, provided that:

* the return types match those in the 3.0 package.
* the implementation specifies a minimum PHP version of 8.0.0 or later
* the implementation depends on `"psr/simple-cache": "^2 || ^3"` so as to exclude the untyped 1.0 version.

Implementing libraries **MAY** add parameter types to their own package in a new minor release, either at the same time as adding return types or in a subsequent release, provided that:

* the parameter types match or widen those in the 2.0 package
* the implementation specifies a minimum PHP version of 8.0 if using mixed or union types or later.
* the implementation depends on `"psr/simple-cache": "^2 || ^3"` so as to exclude the untyped 1.0 version.

Implementing libraries are encouraged, but not required to transition their packages toward the 3.0 version of the package at their earliest convenience.

Calling libraries are encouraged to ensure they are sending the correct types and to update their requirement to `"psr/simple-cache": "^1 || ^2 || ^3"` at their earliest convenience.
