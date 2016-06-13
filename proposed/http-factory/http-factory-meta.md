PSR-N Meta Document
===================

1. Summary
----------

The purpose of this PSR is to provide factory interfaces that define methods to
create PSR-7 objects.

2. Why Bother?
--------------

The current specification for PSR-7 allows for most objects to be modified by
creating immutable copies. However, there are two notable exceptions:

* `StreamInterface` is a mutable object based on a resource that only allows
  the resource to be written to when the resource is writable.
* `UploadedFileInterface` is a read-only object based on a resource that offers
  no modification capabilities.

The former is a significant pain point for PSR-7 middleware, as it can leave
the response in an incomplete state. If the stream attached to the response body
is not seekable or not writable, there is no way to recover from an error
condition in which the body has already been written too.

This scenario can be avoided by providing a factory to create new streams. Due to
the lack of formal standard for HTTP object factories, a developer must rely on
a specific vendor implementation in order to create these objects. Creating a
formal standard for factories will allow for developers to avoid dependency on
specific implementations while having the ability to create new objects when
necessary.

3. Scope
--------

## 3.1 Goals

* Provide a set of interfaces that define methods to create PSR-7 compatible objects.

## 3.2 Non-Goals

* Provide a specific implementation of PSR-7 factories.

4. Approaches
-------------

### 4.1 Chosen Approach

The factory method definition has been chosen based on whether or not the object
can be modified after instantiation. For interfaces that cannot be modified, all
of the object properties must be defined at the time of instantiation.

In the case of `UriInterface` a complete URI may be passed for convenience.

The method names used will not conflict. This allows for a single class to
implement multiple interfaces when appropriate.

#### 4.2 Existing Implementations

All of the current implementations of PSR-7 have defined their own requirements.
In most cases, these requirements are the same or very similar.

#### 4.3 Potential Issues

The most difficult part of defining the method signatures for the interfaces.
As there is no clear declaration in PSR-7 as to what values are explicitly
required, the properties that are read only must be inferred based on whether
the interfaces have methods to copy-and-modify the object.

5. People
---------

### 5.1 Editor(s)

* Woody Gilk, <woody.gilk@gmail.com>

### 5.2 Sponsors

* Roman Tsjupa, <draconyster@gmail.com> (Coordinator)
* Paul M Jones, <pmjones88@gmail.com>

### 5.3 Contributors

* Rasmus Schultz, <rasmus@mindplay.dk>

6. Votes
--------

* [Entrance Vote](https://groups.google.com/forum/#!topic/php-fig/6rZPZ8VglIM)
* **Acceptance Vote:** _(not yet taken)_

7. Relevant Links
-----------------

_**Note:** Order descending chronologically._

* [PSR-7 Middleware Proposal](https://github.com/php-fig/fig-standards/pull/755)
* [PHP-FIG mailing list discussion of middleware](https://groups.google.com/forum/#!topic/php-fig/vTtGxdIuBX8)
* [ircmaxwell All About Middleware](http://blog.ircmaxell.com/2016/05/all-about-middleware.html)
* [shadowhand All About PSR-7 Middleware](http://shadowhand.me/all-about-psr-7-middleware/)
* [AndrewCarterUK PSR-7 Objects Are Not Immutable](http://andrewcarteruk.github.io/programming/2016/05/22/psr-7-is-not-immutable.html)
* [shadowhand Dependency Inversion and PSR-7 Bodies](http://shadowhand.me/dependency-inversion-and-psr-7-bodies/)
* [PHP-FIG mailing list thread discussing factories](https://groups.google.com/d/msg/php-fig/G5pgQfQ9fpA/UWeM1gm1CwAJ)
* [PHP-FIG mailing list thread feedback on proposal](https://groups.google.com/d/msg/php-fig/piRtB2Z-AZs/8UIwY1RtDgAJ)

8. Errata
---------

...
