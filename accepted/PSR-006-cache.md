# Caching Interface

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

*    **Calling Library** - The library or code that actually needs the cache
services. This library will utilize caching services that implement this
standard's interfaces, but will otherwise have no knowledge of the
implementation of those caching services.

*    **Implementing Library** - This library is responsible for implementing
this standard in order to provide caching services to any Calling Library. The
Implementing Library MUST provide classes which implement the
Cache\CacheItemPoolInterface and Cache\CacheItemInterface interfaces.
Implementing Libraries MUST support at minimum TTL functionality as described
below with whole-second granularity.

*    **TTL** - The Time To Live (TTL) of an item is the amount of time between
when that item is stored and it is considered stale. The TTL is normally defined
by an integer representing time in seconds, or a DateInterval object.

*    **Expiration** - The actual time when an item is set to go stale. This is
typically calculated by adding the TTL to the time when an object is stored, but
may also be explicitly set with DateTime object. An item with a 300 second TTL
stored at 1:30:00 will have an expiration of 1:35:00. Implementing Libraries MAY
expire an item before its requested Expiration Time, but MUST treat an item as
expired once its Expiration Time is reached. If a calling library asks for an
item to be saved but does not specify an expiration time, or specifies a null
expiration time or TTL, an Implementing Library MAY use a configured default
duration. If no default duration has been set, the Implementing Library MUST
interpret that as a request to cache the item forever, or for as long as the
underlying implementation supports.

*    **Key** - A string of at least one character that uniquely identifies a
cached item. Implementing libraries MUST support keys consisting of the
characters `A-Z`, `a-z`, `0-9`, `_`, and `.` in any order in UTF-8 encoding and a
length of up to 64 characters. Implementing libraries MAY support additional
characters and encodings or longer lengths, but must support at least that
minimum.  Libraries are responsible for their own escaping of key strings
as appropriate, but MUST be able to return the original unmodified key string.
The following characters are reserved for future extensions and MUST NOT be
supported by implementing libraries: `{}()/\@:`

*    **Hit** - A cache hit occurs when a Calling Library requests an Item by key
and a matching value is found for that key, and that value has not expired, and
the value is not invalid for some other reason. Calling Libraries SHOULD make
sure to verify isHit() on all get() calls.

*    **Miss** - A cache miss is the opposite of a cache hit. A cache miss occurs
when a Calling Library requests an item by key and that value not found for that
key, or the value was found but has expired, or the value is invalid for some
other reason. An expired value MUST always be considered a cache miss.

*    **Deferred** - A deferred cache save indicates that a cache item may not be
persisted immediately by the pool. A Pool object MAY delay persisting a deferred
cache item in order to take advantage of bulk-set operations supported by some
storage engines. A Pool MUST ensure that any deferred cache items are eventually
persisted and data is not lost, and MAY persist them before a Calling Library
requests that they be persisted. When a Calling Library invokes the commit()
method all outstanding deferred items MUST be persisted. An Implementing Library
MAY use whatever logic is appropriate to determine when to persist deferred
items, such as an object destructor, persisting all on save(), a timeout or
max-items check or any other appropriate logic. Requests for a cache item that
has been deferred MUST return the deferred but not-yet-persisted item.

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
leverage PHP's Serializable interface, `__sleep()` or `__wakeup()` magic methods,
or similar language functionality if appropriate.

All data passed into the Implementing Library MUST be returned exactly as
passed. That includes the variable type. That is, it is an error to return
(string) 5 if (int) 5 was the value saved.  Implementing Libraries MAY use PHP's
serialize()/unserialize() functions internally but are not required to do so.
Compatibility with them is simply used as a baseline for acceptable object values.

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

## Error handling

While caching is often an important part of application performance, it should never
be a critical part of application functionality. Thus, an error in a cache system SHOULD NOT
result in application failure.  For that reason, Implementing Libraries MUST NOT
throw exceptions other than those defined by the interface, and SHOULD trap any errors
or exceptions triggered by an underlying data store and not allow them to bubble.

An Implementing Library SHOULD log such errors or otherwise report them to an
administrator as appropriate.

If a Calling Library requests that one or more Items be deleted, or that a pool be cleared,
it MUST NOT be considered an error condition if the specified key does not exist. The
post-condition is the same (the key does not exist, or the pool is empty), thus there is
no error condition.

## Interfaces

### CacheItemInterface

CacheItemInterface defines an item inside a cache system.  Each Item object
MUST be associated with a specific key, which can be set according to the
implementing system and is typically passed by the Cache\CacheItemPoolInterface
object.

The Cache\CacheItemInterface object encapsulates the storage and retrieval of
cache items. Each Cache\CacheItemInterface is generated by a
Cache\CacheItemPoolInterface object, which is responsible for any required
setup as well as associating the object with a unique Key.
Cache\CacheItemInterface objects MUST be able to store and retrieve any type of
PHP value defined in the Data section of this document.

Calling Libraries MUST NOT instantiate Item objects themselves. They may only
be requested from a Pool object via the getItem() method.  Calling Libraries
SHOULD NOT assume that an Item created by one Implementing Library is
compatible with a Pool from another Implementing Library.

~~~php
<?php

namespace Psr\Cache;

/**
 * CacheItemInterface defines an interface for interacting with objects inside a cache.
 */
interface CacheItemInterface
{
    /**
     * Returns the key for the current cache item.
     *
     * The key is loaded by the Implementing Library, but should be available to
     * the higher level callers when needed.
     *
     * @return string
     *   The key string for this cache item.
     */
    public function getKey();

    /**
     * Retrieves the value of the item from the cache associated with this object's key.
     *
     * The value returned must be identical to the value originally stored by set().
     *
     * If isHit() returns false, this method MUST return null. Note that null
     * is a legitimate cached value, so the isHit() method SHOULD be used to
     * differentiate between "null value was found" and "no value was found."
     *
     * @return mixed
     *   The value corresponding to this cache item's key, or null if not found.
     */
    public function get();

    /**
     * Confirms if the cache item lookup resulted in a cache hit.
     *
     * Note: This method MUST NOT have a race condition between calling isHit()
     * and calling get().
     *
     * @return bool
     *   True if the request resulted in a cache hit. False otherwise.
     */
    public function isHit();

    /**
     * Sets the value represented by this cache item.
     *
     * The $value argument may be any item that can be serialized by PHP,
     * although the method of serialization is left up to the Implementing
     * Library.
     *
     * @param mixed $value
     *   The serializable value to be stored.
     *
     * @return static
     *   The invoked object.
     */
    public function set($value);

    /**
     * Sets the expiration time for this cache item.
     *
     * @param \DateTimeInterface|null $expiration
     *   The point in time after which the item MUST be considered expired.
     *   If null is passed explicitly, a default value MAY be used. If none is set,
     *   the value should be stored permanently or for as long as the
     *   implementation allows.
     *
     * @return static
     *   The called object.
     */
    public function expiresAt($expiration);

    /**
     * Sets the expiration time for this cache item.
     *
     * @param int|\DateInterval|null $time
     *   The period of time from the present after which the item MUST be considered
     *   expired. An integer parameter is understood to be the time in seconds until
     *   expiration. If null is passed explicitly, a default value MAY be used.
     *   If none is set, the value should be stored permanently or for as long as the
     *   implementation allows.
     *
     * @return static
     *   The called object.
     */
    public function expiresAfter($time);

}
~~~

### CacheItemPoolInterface

The primary purpose of Cache\CacheItemPoolInterface is to accept a key from the
Calling Library and return the associated Cache\CacheItemInterface object.
It is also the primary point of interaction with the entire cache collection.
All configuration and initialization of the Pool is left up to an Implementing
Library.

~~~php
<?php

namespace Psr\Cache;

/**
 * CacheItemPoolInterface generates CacheItemInterface objects.
 */
interface CacheItemPoolInterface
{
    /**
     * Returns a Cache Item representing the specified key.
     *
     * This method must always return a CacheItemInterface object, even in case of
     * a cache miss. It MUST NOT return null.
     *
     * @param string $key
     *   The key for which to return the corresponding Cache Item.
     *
     * @throws InvalidArgumentException
     *   If the $key string is not a legal value a \Psr\Cache\InvalidArgumentException
     *   MUST be thrown.
     *
     * @return CacheItemInterface
     *   The corresponding Cache Item.
     */
    public function getItem($key);

    /**
     * Returns a traversable set of cache items.
     *
     * @param string[] $keys
     *   An indexed array of keys of items to retrieve.
     *
     * @throws InvalidArgumentException
     *   If any of the keys in $keys are not a legal value a \Psr\Cache\InvalidArgumentException
     *   MUST be thrown.
     *
     * @return array|\Traversable
     *   A traversable collection of Cache Items keyed by the cache keys of
     *   each item. A Cache item will be returned for each key, even if that
     *   key is not found. However, if no keys are specified then an empty
     *   traversable MUST be returned instead.
     */
    public function getItems(array $keys = array());

    /**
     * Confirms if the cache contains specified cache item.
     *
     * Note: This method MAY avoid retrieving the cached value for performance reasons.
     * This could result in a race condition with CacheItemInterface::get(). To avoid
     * such situation use CacheItemInterface::isHit() instead.
     *
     * @param string $key
     *   The key for which to check existence.
     *
     * @throws InvalidArgumentException
     *   If the $key string is not a legal value a \Psr\Cache\InvalidArgumentException
     *   MUST be thrown.
     *
     * @return bool
     *   True if item exists in the cache, false otherwise.
     */
    public function hasItem($key);

    /**
     * Deletes all items in the pool.
     *
     * @return bool
     *   True if the pool was successfully cleared. False if there was an error.
     */
    public function clear();

    /**
     * Removes the item from the pool.
     *
     * @param string $key
     *   The key to delete.
     *
     * @throws InvalidArgumentException
     *   If the $key string is not a legal value a \Psr\Cache\InvalidArgumentException
     *   MUST be thrown.
     *
     * @return bool
     *   True if the item was successfully removed. False if there was an error.
     */
    public function deleteItem($key);

    /**
     * Removes multiple items from the pool.
     *
     * @param string[] $keys
     *   An array of keys that should be removed from the pool.

     * @throws InvalidArgumentException
     *   If any of the keys in $keys are not a legal value a \Psr\Cache\InvalidArgumentException
     *   MUST be thrown.
     *
     * @return bool
     *   True if the items were successfully removed. False if there was an error.
     */
    public function deleteItems(array $keys);

    /**
     * Persists a cache item immediately.
     *
     * @param CacheItemInterface $item
     *   The cache item to save.
     *
     * @return bool
     *   True if the item was successfully persisted. False if there was an error.
     */
    public function save(CacheItemInterface $item);

    /**
     * Sets a cache item to be persisted later.
     *
     * @param CacheItemInterface $item
     *   The cache item to save.
     *
     * @return bool
     *   False if the item could not be queued or if a commit was attempted and failed. True otherwise.
     */
    public function saveDeferred(CacheItemInterface $item);

    /**
     * Persists any deferred cache items.
     *
     * @return bool
     *   True if all not-yet-saved items were successfully saved or there were none. False otherwise.
     */
    public function commit();
}
~~~

### CacheException

This exception interface is intended for use when critical errors occur,
including but not limited to *cache setup* such as connecting to a cache server
or invalid credentials supplied.

Any exception thrown by an Implementing Library MUST implement this interface.

~~~php
<?php

namespace Psr\Cache;

/**
 * Exception interface for all exceptions thrown by an Implementing Library.
 */
interface CacheException
{
}
~~~

### InvalidArgumentException

~~~php
<?php

namespace Psr\Cache;

/**
 * Exception interface for invalid cache arguments.
 *
 * Any time an invalid argument is passed into a method it must throw an
 * exception class which implements Psr\Cache\InvalidArgumentException.
 */
interface InvalidArgumentException extends CacheException
{
}
~~~
