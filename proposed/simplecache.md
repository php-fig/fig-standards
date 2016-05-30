Common Interface for Caching libraries
================

This document describes a simple yet extensible interface for a cache item and
a cache driver.

The key words "MUST", "MUST NOT", "REQUIRED", "SHALL", "SHALL NOT", "SHOULD",
"SHOULD NOT", "RECOMMENDED", "MAY", and "OPTIONAL" in this document are to be
interpreted as described in [RFC 2119][].

The final implementations MAY be able to decorate the objects with more
functionality than the one proposed but they MUST implement the indicated
interfaces/functionality first.

[RFC 2119]: http://tools.ietf.org/html/rfc2119

1. Specification
-----------------

## 1.1 Introduction

Caching is a common way to improve the performance of any project, making
caching libraries one of the most common features of many frameworks and
libraries. This has led to a situation where many libraries roll their own
caching libraries, with various levels of functionality. These differences are
causing developers to have to learn multiple systems which may or may not
provide the functionality they need. In addition, the developers of caching
libraries themselves face a choice between only supporting a limited number
of frameworks or creating a large number of adapter classes.

A common interface for caching systems will solve these problems. Library and
framework developers can count on the caching systems working the way they're
expecting, while the developers of caching systems will only have to implement
a single set of interfaces rather than a whole assortment of adapters.

### 1.2 Definitions

*    **TTL** - The Time To Live (TTL) of an item is the amount of time between
when that item is stored and it is considered stale. The TTL is normally defined
by an integer representing time in seconds.

*    **Expiration** - The actual time when an item is set to go stale.

    An item with a 300 second TTL stored at 1:30:00 will have an expiration at
    1:35:00.

*    **Key** - A string that uniquely identifies the cached item.

*    **Cache** - An object that implements the `Psr\SimpleCache\CacheInterface` interface.


### 1.3 Cache

Implementations MAY provide a mechanism for a user to specify a default TTL
if one is not specified for a specific cache item.  If no user-specified default
is provided implementations MUST default to the maximum legal value allowed by
the underlying implementation.  If the underlying implementation does not
support TTL, the user-specified TTL MUST be silently ignored.

If your implementation is expected to work across many different platforms then
it is recommended to have your cache keys consist of no more than 32 ASCII characters
 or the following symbols. ``{}()/\@:``

2. Interfaces
-------------

### 2.1 CacheInterface

This is the base interface class that developers should be looking to begin with. It provides the most basic functionality imaginable by a cache server which entails basic reading, writing and deleting of cache items.
It will provide a generic API for library developers to allow applications to communicate to all popular cache backends.

```php

<?php

namespace Psr\SimpleCache;

interface CacheInterface
{

    const TTL_MINUTE = 60;
    const TTL_HOUR = 3600;
    const TTL_DAY = 86400;

    /**
     * Here we pass in a cache key to be fetched from the cache.
     *
     * @param string $key The unique key of this item in the cache
     *
     * @return mixed The value of the item from the cache
     */
    public function get($key);

    /**
     * Persisting our data in the cache, uniquely referenced by a key with an optional expiration TTL time.
     *
     * @param string $key The key of the item to store
     * @param mixed $value The value of the item to store
     * @param null|integer|DateInterval $ttl Optional. The TTL value of this item. If no value is sent and the driver supports TTL
     *                            then the library may set a default value for it or let the driver take care of that.
     *
     * @return boolean True on success and false otherwise
     */
    public function set($key, $value, $ttl = null);

    /**
     * Remove an item from the cache by its unique key
     *
     * @param string $key The unique cache key of the item to remove
     *
     * @return boolean    Returns true on success and otherwise false
     */
    public function remove($key);

    /**
     * This will wipe out the entire cache's keys
     *
     * @return boolean Returns true on success and otherwise false
     */
    public function clear();

}

```

### 2.3 MultipleInterface

This interface has methods for dealing with multiple sets of cache entries such as writing, reading or deleting multiple cache entries at a time. This is really useful when you have lots of cache reads/writes to perform then you can perform your operations in a single call to the cache server cutting down latency times dramatically.

```php

<?php

namespace Psr\SimpleCache;

interface MultipleInterface
{

    /**
     * Obtain multiple cache items by their unique keys
     *
     * @param array|Traversable $keys A list of keys that can obtained in a single operation.
     *
     * @return array An array of items to cache. Cache keys that don't exist will be part of the return array with a value of NULL.
      *
     */
    public function getMultiple($keys);

    /**
     * Persisting a set of key => value pairs in the cache, with an optional TTL.
     *
     * @param array|Traversable   $items An array of key => value pairs for a multiple-set operation.
     * @param null|integer|DateInterval $ttl   Optional. The amount of seconds from the current time that the item will exist in the cache for. I this is null then the cache backend will fall back to its own default behaviour.
     *
     * @return boolean The result of the multiple-set operation
     */
    public function setMultiple($items, $ttl = null);

    /**
     * Remove multiple cache items in a single operation
     *
     * @param array|Traversable $keys The array of string-based Keys to be removed
     *
     * @return array An array of 'key' => result, elements. Each array row has the key being deleted
     *               and the result of that operation. The result will be a boolean of true or false
     *               representing if the cache item was removed or not.
     */
    public function removeMultiple($keys);

}
```

### 2.4 IncrementableInterface

This interface provides the ability to increment and decrement cache entries by their specified value. Some cache backends support this natively so that you don't have to read the item and then increment it and write it back to the cache server, this can be done in a single call to the cache server since it's natively supported by many modern cache servers.

```php

<?php

namespace Psr\SimpleCache;

interface IncrementableInterface
{

    /**
     * Increment a value in the cache by its step value, which defaults to 1
     *
     * @param string  $key  The cache item key
     * @param integer $step The value to increment by, defaulting to 1
     *
     * @return boolean True on success and false on failure
     */
    public function increment($key, $step = 1);

    /**
     * Decrement a value in the cache by its step value, which defaults to 1
     *
     * @param string  $key  The cache item key
     * @param integer $step The value to decrement by, defaulting to 1
     *
     * @return boolean True on success and false on failure
     */
    public function decrement($key, $step = 1);

}
```

3. Credits
----------

Contributors:
@evert, @dlsniper, @dannym87
