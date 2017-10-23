# Container interface

This document describes a common interface for dependency injection containers.

The goal set by `ContainerInterface` is to standardize how frameworks and libraries make use of a
container to obtain objects and parameters (called *entries* in the rest of this document).

The key words "MUST", "MUST NOT", "REQUIRED", "SHALL", "SHALL NOT", "SHOULD",
"SHOULD NOT", "RECOMMENDED", "MAY", and "OPTIONAL" in this document are to be
interpreted as described in [RFC 2119][].

The word `implementor` in this document is to be interpreted as someone
implementing the `ContainerInterface` in a dependency injection-related library or framework.
Users of dependency injection containers (DIC) are referred to as `user`.

[RFC 2119]: http://tools.ietf.org/html/rfc2119

## 1. Specification

### 1.1 Basics

#### 1.1.1 Entry identifiers

An entry identifier is any PHP-legal string of at least one character that uniquely identifies an item within a container.  An entry identifier is an opaque string, so callers SHOULD NOT assume that the structure of the string carries any semantic meaning.

#### 1.1.2 Reading from a container

- The `Psr\Container\ContainerInterface` exposes two methods: `get` and `has`.

- `get` takes one mandatory parameter: an entry identifier, which MUST be a string.
  `get` can return anything (a *mixed* value), or throw a `NotFoundExceptionInterface` if the identifier
  is not known to the container. Two successive calls to `get` with the same
  identifier SHOULD return the same value. However, depending on the `implementor`
  design and/or `user` configuration, different values might be returned, so
  `user` SHOULD NOT rely on getting the same value on 2 successive calls.

- `has` takes one unique parameter: an entry identifier, which MUST be a string.
  `has` MUST return `true` if an entry identifier is known to the container and `false` if it is not.
  If `has($id)` returns false, `get($id)` MUST throw a `NotFoundExceptionInterface`.

### 1.2 Exceptions

Exceptions directly thrown by the container SHOULD implement the
[`Psr\Container\ContainerExceptionInterface`](#container-exception).

A call to the `get` method with a non-existing id MUST throw a
[`Psr\Container\NotFoundExceptionInterface`](#not-found-exception).

### 1.3 Recommended usage

Users SHOULD NOT pass a container into an object so that the object can retrieve *its own dependencies*.
This means the container is used as a [Service Locator](https://en.wikipedia.org/wiki/Service_locator_pattern)
which is a pattern that is generally discouraged.

Please refer to section 4 of the META document for more details.

## 2. Package

The interfaces and classes described as well as relevant exceptions are provided as part of the
[psr/container](https://packagist.org/packages/psr/container) package.

Packages providing a PSR container implementation should declare that they provide `psr/container-implementation` `1.0.0`.

Projects requiring an implementation should require `psr/container-implementation` `1.0.0`.

## 3. Interfaces

<a name="container-interface"></a>
### 3.1. `Psr\Container\ContainerInterface`

~~~php
<?php
namespace Psr\Container;

/**
 * Describes the interface of a container that exposes methods to read its entries.
 */
interface ContainerInterface
{
    /**
     * Finds an entry of the container by its identifier and returns it.
     *
     * @param string $id Identifier of the entry to look for.
     *
     * @throws NotFoundExceptionInterface  No entry was found for **this** identifier.
     * @throws ContainerExceptionInterface Error while retrieving the entry.
     *
     * @return mixed Entry.
     */
    public function get($id);

    /**
     * Returns true if the container can return an entry for the given identifier.
     * Returns false otherwise.
     *
     * `has($id)` returning true does not mean that `get($id)` will not throw an exception.
     * It does however mean that `get($id)` will not throw a `NotFoundExceptionInterface`.
     *
     * @param string $id Identifier of the entry to look for.
     *
     * @return bool
     */
    public function has($id);
}
~~~

<a name="container-exception"></a>
### 3.2. `Psr\Container\ContainerExceptionInterface`

~~~php
<?php
namespace Psr\Container;

/**
 * Base interface representing a generic exception in a container.
 */
interface ContainerExceptionInterface
{
}
~~~

<a name="not-found-exception"></a>
### 3.3. `Psr\Container\NotFoundExceptionInterface`

~~~php
<?php
namespace Psr\Container;

/**
 * No entry was found in the container.
 */
interface NotFoundExceptionInterface extends ContainerExceptionInterface
{
}
~~~
