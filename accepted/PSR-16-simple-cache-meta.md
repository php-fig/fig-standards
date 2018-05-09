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
