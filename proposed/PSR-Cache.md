## Introduction

Caching is a common way to improve the performance of any project, making
caching libraries one of the most common features of many frameworks and
libraries. This has lead to a situation where many libraries roll their own
caching libraries, with various levels of functionality. These differences are
causing developers to have to learn multiple systems which may or may not
provide the functionality they need. In addition, the developers of caching
libraries themselves face a choice between only supporting a limited number
of frameworks or creating a large number of adapter classes.

A common interface for caching systems will solve these problems. Library and
framework developers can count on the caching systems working the way they're
expecting, while the developers of caching systems will only have to implement
a single set of interfaces rather than a whole assortment of adapters.

The key words "MUST", "MUST NOT", "REQUIRED", "SHALL", "SHALL NOT", "SHOULD",
"SHOULD NOT", "RECOMMENDED", "MAY", and "OPTIONAL" in this document are to be
interpreted as described in [RFC 2119][].

[RFC 2119]: http://tools.ietf.org/html/rfc2119

## Goal

The goal of this PSR is to allow developers to create cache-aware libraries that
can be integrated into existing frameworks and systems without the need for
custom development.


## Definitions

*    **TTL** - The Time To Live (TTL) of an item is the amount of time between
when that item is stored and it is considered stale. The TTL is normally defined
by an integer representing time in seconds, or a DateInterval object.

*    **Expiration** - The actual time when an item is set to go stale. This it
typically calculated by adding the TTL to the time when an object is stored, but
may also be explicitly set with DateTime object.

    An item with a 300 second TTL stored at 1:30:00 will have an expiration at
    1:35:00.

Implementing Libraries MAY expire an item before its requested Expiration Time,
but MUST treat an item as expired once its Expiration Time is reached.

*    **Key** - A string that uniquely identifies a cached item. Implementing
libraries MUST support keys consisting of the characters `A-Z`, `a-z`, `0-9`,
and `_` in any order in UTF-8 encoding and a length of up to 64 characters.
Implementing libraries MAY support additional characters and encodings or longer
lengths, but must support at least that minimum.  Libraries are responsible for
their own escaping of key strings as appropriate, but MUST be able to return
the original unmodified key string. The following characters are reserved for
future extensions and MUST NOT be supported by implementing libraries: `{}()/\@:`

*    **Hit** - A cache hit occurs when a Calling Library requests an Item by key
and a matching value is found for that key, and that value has not expired, and
the value is not invalid for some other reason.

*    **Miss** - A cache miss is the opposite of a cache hit. A cache hit occurs
when a Calling Library requests an item by key and that value not found for that
key, or the value was found but has expired, or the value is invalid for some
other reason. An expired value MUST always be considered a cache miss.

*    **Calling Library** - The library or code that actually needs the cache
services. This library will utilize caching services that implement this
standard's interfaces, but will otherwise have no knowledge of the
implementation of those caching services.

*    **Implementing Library** - This library is responsible for implementing
this standard in order to provide caching services to any Calling Library. The
Implementing Library MUST provide classes which implement the Cache\PoolInterface
and Cache\ItemInterface interfaces.


## Data

Implementing libraries MUST support all serializable PHP data types, including:

*    **Strings** - Character strings of arbitrary size in any PHP-compatible encoding.
*    **Integers** - All integers of any size supported by PHP, up to 64-bit signed.
*    **Floats** - All signed floating point values.
*    **Boolean** - True and False.
*    **Null** - The actual null value.
*    **Arrays** - Indexed, associative and multidimensional arrays of arbitrary depth.
*    **Object** - Any object that supports lossless serialization and
deserialization such that $o == unserialize(serialize($o)). Objects MAY
leverage PHP's Serializable interface, __sleep()/__wakeup() magic methods, or
similar language functionality if appropriate.

All data passed into the Implementing Library MUST be returned exactly as
passed. That includes the variable type. That is, it is an error to return
(string) 5 if (int) 5 was the value saved.  Note that Implementing Libraries
are not required to use PHP's serialize()/unserialize() functions internally.
Compatibility with them is simply used as a compatibility baseline.

If it is not possible to return the exact saved value for any reason, implementing
libraries MUST respond with a cache miss rather than corrupted data.

## Key Concepts

### Pool

The Pool represents a collection of items in a caching system. The pool is
a logical Repository of all items it contains.  All cacheable items are retrieved
from the Pool as an Item object, and all interaction with the whole universe of
cached objects happens through the Pool.

### Items

An Item represents a single key/value pair within a Pool. The key is the primary
unique identifier for an Item and MUST be immutable. The Value MAY be changed
at any time.


## Interfaces


### Cache\PoolInterface

The primary purpose of Cache\PoolInterface is to accept a key from the Calling
Library and return the associated Cache\ItemInterface object. It is also the
primary point of interaction with the entire cache collection. All configuration
and initialization of the Pool is left up to an Implementing Library.

```php
<?php
namespace Psr\Cache;

/**
 * \Psr\Cache\PoolInterface generates Cache\Item objects.
 */
interface PoolInterface
{
    /**
     * Returns a Cache Item representing the specified key.
     *
     * This method must always return an ItemInterface object, even in case of
     * a cache miss. It MUST NOT return null.
     *
     * @param string $key
     *   The key for which to return the corresponding Cache Item.
     * @return \Psr\Cache\ItemInterface
     *   The corresponding Cache Item.
     * @throws \InvalidArgumentException
     *   If the $key string is not a legal value an \InvalidArgumentException
     *   MUST be thrown.
     */
    function getItem($key);

    /**
     * Returns a traversable set of cache items.
     *
     * @param array $keys
     *   An indexed array of keys of items to retrieve.
     * @return \Traversable
     *   A traversable collection of Cache Items in the same order as the $keys
     *   parameter. If no items are found an empty Traversable collection will
     *   be returned.
     */
    function getItems(array $keys);

    /**
     * Deletes all items in the pool.
     *
     * @return \Psr\Cache\PoolInterface
     *   The current pool.
     */
    function clear();
}
```


### Cache\Item

The Cache\ItemInterface object encapsulates the storage and retrieval of cache items.
Each Cache\ItemInterface is generated by a Cache\PoolInterface object, which is
responsible for any required setup as well as associating the object with a
unique Key (how this is accomplished is the responsibility of the Implementing
Library). Cache\ItemInterface objects can store and retrieve any type of PHP
value defined in the Data section of this document.

```php
<?php
namespace Psr\Cache;

/**
 * Cache\ItemInterface defines an interface for interacting with objects inside a cache.
 *
 * Cache\ItemInterface defines an item inside a cache system, which can be
 * filled with any PHP value capable of being serialized. Each
 * Cache\ItemInterface object should be associated with a specific key, which
 * can be set according to the implementing system and is typically passed by
 * the Cache\PoolInterface object.
 */
interface ItemInterface
{
    /**
     * Returns the key for the current cache item.
     *
     * The key is loaded by the Implementing Library, but should be available to
     * the higher level callers when needed.
     *
     * @return string
     */
    function getKey();

    /**
     * Retrieves the item from the cache associated with this objects key.
     *
     * Value returned must be identical to the value original stored by set().
     *
     * If the cache is empty then null should be returned. However, null is also
     * a valid cache item, so the isMiss function should be used to check
     * validity.
     *
     * @return mixed
     */
    function get();

    /**
     * Stores a value into the cache.
     *
     * The $value argument can be any item that can be serialized by PHP,
     * although the method of serialization is left up to the Implementing
     * Library.
     *
     * The $ttl can be defined in a number of ways. As an integer or
     * DateInverval object the argument defines how long before the cache should
     * expire. As a DateTime object the argument defines the actual expiration
     * time of the object. Implementations are allowed to use a lower time than
     * passed, but should not use a longer one.
     *
     * If no $ttl is passed then the item can be stored indefinitely or a
     * default value can be set by the Implementing Library.
     *
     * Returns true if the item was successfully stored.
     *
     * @param mixed $value
     * @param int|DateInterval|DateTime $ttl
     * @return bool
     */
    function set($value = null, $ttl = null);

    /**
     * Confirms if the cache item exists in the cache
     *
     * @return bool
     */
    function isHit();

    /**
     * Removes the current key from the cache.
     *
     * Returns true if the item is no longer present (either because it was
     * removed or was not present to begin with).
     *
     * @return bool
     */
    function delete();
}
```
