Common Interface for data querying
==================================

This document describes common interfaces to query data. These data can be from different sources, from in-memory data to databases, as well as filesystem files.

The key words "MUST", "MUST NOT", "REQUIRED", "SHALL", "SHALL NOT", "SHOULD",
"SHOULD NOT", "RECOMMENDED", "MAY", and "OPTIONAL" in this document are to be
interpreted as described in [RFC 2119][].

The final implementations MAY decorate the objects with more
functionality than the one proposed but they MUST implement the indicated
interfaces/functionality first.

[RFC 2119]: http://tools.ietf.org/html/rfc2119

## 1. Specification

### 1.1 Goal

There are numerous ways of querying data from so many sources. Each system has its very own way of doing it. For instance, Doctrine ORM and Symfony Finder are both querying data with query builders (or alike), but there is no standardized interface to implement such data querying.

The goal of this PSR it to define common interfaces to query data, no matter where they come from (arrays, iterators, etc.) or their format.

### 1.2 Definitions

*    **Query** - The object that will be used to query data. It receives a source as an `iterable`, which allows processing of arrays as well as other type of collections like generators.

*    **Modifier** - A modifier is an action applied to the source, which will be executed before each operation. There can be multiple modifiers on a Query, for instance a `where` clause, an `order by`, a `limit` or even an `offset`. Operations are applied _after_ modifiers.

*    **Operation** - An operation is a one-time action applied to a processed source, after all modifiers has been applied. An operation can return any type of data: generators, scalars, etc.

*    **Context** - What holds additional information to be passed to the query, modifiers and operations in the case they need it.

## 2. Interfaces

### 2.1 QueryInterface

Has the ability to query an iterable source.

```php
namespace Psr\Query;

use Psr\Query\QueryContextInterface;
use Psr\Query\QueryModifierInterface;
use Psr\Query\QueryOperationInterface;

/**
 * Defines an object which will execute modifiers and operations to query data.
 * A QueryInterface could receive any iterable depending on the implementation. This could go from the
 * basic array of data to generators.
 */
interface QueryInterface
{
    /**
     * Creates a new object to query data.
     *
     * @param iterable $source
     *      The source to apply manipulations on.
     *
     * @param QueryContextInterface|null $context
     *      The optional context that forward needed information to the Query for its execution.
     *
     * @return static
     */
    public static function from(iterable $source, QueryContextInterface $context = null): static;

    /**
     * Get the context of the current Query, possibly modified by the latter.
     *
     * @return QueryContextInterface
     */
    public function getContext(): QueryContextInterface;

    /**
     * Gets the source of the Query.
     *
     * @return iterable
     */
    public function getSource(): iterable;

    /**
     * Applies a modifier to the Query.
     *
     * @param QueryModifierInterface $modifier
     *      The actual modifier to apply.
     *
     * @return static
     */
    public function applyModifier(QueryModifierInterface $modifier): static;

    /**
     * Applies an operation to the Query.
     *
     * @param QueryOperationInterface $operation
     *      The actual operation to apply.
     *
     * @return mixed
     */
    public function applyOperation(QueryOperationInterface $operation): mixed;
}
```

### 2.2 QueryContextInterface

Has the ability to hold and transmit information to the Query, which would be needed for modifiers and operations to be applied.

```php
namespace Psr\Query;

/**
 * Defines the context of a Query. This could be used to pass additional data to the query, such as
 * already used alias in the current Query context.
 */
interface QueryContextInterface
{
}
```

### 2.3 QueryModifierInterface

Applies some modification on Query's source, before applying a final operation on the processed source.

```php
namespace Psr\Query;

use Psr\Query\QueryInterface;

/**
 * Defines a Query modifier. A modifier is applied before any operation. This could be a `where` clause, as well
 * as an ordering, a shuffling, limiting the max number of results, etc.
 */
interface QueryModifierInterface
{
    /**
     * Applies the modifier to the given source, and returns the result of this modifier.
     *
     * @param QueryInterface $query
     *      The source to apply the modifier on.
     *
     *      Optional context if needed by the modifier.
     *
     * @return iterable
     *      The modified source.
     */
    public function apply(QueryInterface $query): iterable;
}
```

### 2.4 QueryOperationInterface

Applies a final operation to source after modifiers has been applied, and returns the result. The result can be of any type, according to what the operation actually does.

```php
namespace Psr\Query;

use Psr\Query\QueryInterface;

/**
 * Defines a final operation done to the source, after modifiers has been applied. An operation can be
 * a simple concatenation, selecting data, get an average/min/max value, etc.
 */
interface QueryOperationInterface
{
    /**
     * @param QueryInterface $query
     *      The source to apply the operation on.
     *
     * @return mixed
     *      The result of the operation. This can be any type of data, depending on what the operation actually does.
     */
    public function apply(QueryInterface $query): mixed;
}
```