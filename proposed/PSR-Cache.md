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
can also be explicitly set with DateTime object.

    An item with a 300 second TTL stored at 1:30:00 will have an expiration at
    1:35:00.

*    **Key** - A string that uniquely identifies the cached item. Implementing
Libraries are responsible for any encoding or escaping required by their
backends, but must be able to supply the original key if needed. Keys should not
contain the special characters listed:

	{}()/\@

*    **Miss** - An item is considered missing from the cache when it isn't there
or has an expiration in the past. Additional Miss conditions can be defined by
the Implementing Library as long as these conditions are met (at no point should
an expired item not be considered a miss).

*    **Calling Library** - The library or code that actually needs the cache
services. This library will utilize caching services that implement this
standard's interfaces, but will otherwise have no knowledge of the
implementation of those caching services.

*    **Implementing Library** - This library is responsible for implementing
this standard in order to provide caching services to any Calling Library. The
Implementing Library must provide classes which implement the Cache\Pool and
Cache\Item interfaces.


## Data


Acceptable data includes all serializable PHP data types-

*    **Strings** - Simple, complex and large strings of any encoding.
*    **Integers** - Positive, negative and large integers (>32 bit).
*    **Floats** - Positive, negative and large.
*    **Boolean**- true, false.
*    **Null** - not a wrapper or object, but the actual null value.
*    **Arrays** - indexed, associative and multidimensional.
*    **Object** - those that support the PHP serialize functionality.

All data passed into the Implementing Library must be returned exactly as
passed. If this is not possible for whatever reason then it is preferable to
respond with a cache miss than with corrupted data.


## Key Concepts

### Pool

The pool represents a collection of items in a caching system. The pool
conceptually contains all of the items. When you want to get something out of
the caching system, you use the Pool to create the Item object. A Pool can
return one Item or many Items, and the pool can be used to clear all of the
items in the cache.


### Items

An item represents a single key/value pair inside of a caching system. It is
associated with a "key" that can't be changed, and a value that can be set or
retrieved. The status of the item (hit or miss) is relevant only to that
particular item.


## Interfaces


### Cache\PoolInterface

The main focus of Cache\PoolInterface is to accept a key from the Calling
Library and return the associated Cache\ItemInterface object. The majority of the Pool
object's implementation is up to the Implementing Library, including all
configuration, initialization and the injection itself into the Calling Library.

Items can be retrieved from the Cache\PoolInterface individually using the getItem
function, or in groups by retrieving an Iterator object from the
getItemIterator function.

```php
<?php
namespace Psr\Cache;

/**
 * Cache\PoolInterface generates Cache\Item objects.
 */
interface PoolInterface
{
    /**
     * Returns objects which implement the Cache\PoolInterface interface.
     *
     * Provided key must be unique for each item in the cache. Implementing
     * Libraries are responsible for any encoding or escaping required by their
     * backends, but must be able to supply the original key if needed. Keys
     * should not contain the special characters listed:
     *  {}()/\@
     *
     * @param string $key
     * @return Psr\Cache\ItemInterface
     */
    function getItem($key);

    /**
     * Returns a group of cache objects as an \Iterator
     *
     * Bulk lookups can often by streamlined by backend cache systems. The
     * returned iterator will contain a Cache\Item for each key passed.
     *
     * @param array $keys
     * @return \Iterator
     */
    function getItems($keys);

    /**
     * Clears the cache pool of all items.
     *
     * @return bool
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
