Common Interface for Caching libraries
================

This document describes a simple yet extensible interface for a cache item and
a cache driver.

The key words "MUST", "MUST NOT", "REQUIRED", "SHALL", "SHALL NOT", "SHOULD",
"SHOULD NOT", "RECOMMENDED", "MAY", and "OPTIONAL" in this document are to be
interpreted as described in [RFC 2119][].

The final implementations MAY decorate the objects with more
functionality than the one proposed but they MUST implement the indicated
interfaces/functionality first.

[RFC 2119]: http://tools.ietf.org/html/rfc2119

1. Specification
-----------------

## 1.1 Introduction

Caching is a common way to improve the performance of any project, making
caching libraries one of the most common features of many frameworks and
libraries. Interoperability at this level means libraries can drop their
own caching implementations and easily rely on the one given to them by the
framework, or another dedicated cache library.

PSR-6 solves this problem already, but in a rather formal and verbose way for
what the most simple use cases need. This simpler approach aims to build a
standardized layer of simplicity on top of the existing PSR-6 interfaces.


### 1.2 Definitions

Definitions for Calling Library, Implementing Library, TTL, Expiration and Key
are copied from PSR-6 as the same assumptions are true.

*    **Calling Library** - The library or code that actually needs the cache
services. This library will utilize caching services that implement this
standard's interfaces, but will otherwise have no knowledge of the
implementation of those caching services.

*    **Implementing Library** - This library is responsible for implementing
this standard in order to provide caching services to any Calling Library. The
Implementing Library MUST provide a class implementing the Psr\SimpleCache\CacheInterface interface.
Implementing Libraries MUST support at minimum TTL functionality as described
below with whole-second granularity.

*    **TTL** - The Time To Live (TTL) of an item is the amount of time between
when that item is stored and it is considered stale. The TTL is normally defined
by an integer representing time in seconds, or a DateInterval object.

*    **Expiration** - The actual time when an item is set to go stale. This it
typically calculated by adding the TTL to the time when an object is stored, but
may also be explicitly set with DateTime object.

    An item with a 300 second TTL stored at 1:30:00 will have an expiration of
    1:35:00.

    Implementing Libraries MAY expire an item before its requested Expiration Time,
but MUST treat an item as expired once its Expiration Time is reached. If a calling
library asks for an item to be saved but does not specify an expiration time, or
specifies a null expiration time or TTL, an Implementing Library MAY use a configured
default duration. If no default duration has been set, the Implementing Library
MUST interpret that as a request to cache the item forever, or for as long as the
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

*    **Cache** - An object that implements the `Psr\SimpleCache\CacheInterface` interface.

*    **Cache Misses** - A cache miss will return null and therefore detecting
if one stored null is not possible. This is the main deviation from PSR-6's
assumptions.


### 1.3 Cache

Implementations MAY provide a mechanism for a user to specify a default TTL
if one is not specified for a specific cache item.  If no user-specified default
is provided implementations MUST default to the maximum legal value allowed by
the underlying implementation.  If the underlying implementation does not
support TTL, the user-specified TTL MUST be silently ignored.


2. Interfaces
-------------

### 2.1 CacheInterface

The cache interface provides the most basic functionality of cache servers which
entails basic reading, writing and deleting of single cache items.

In addition it has methods for dealing with multiple sets of cache entries such as writing, reading or
deleting multiple cache entries at a time. This is useful when you have lots of cache reads/writes
to perform, and lets you perform your operations in a single call to the cache server cutting down latency
times dramatically.

Finally for counters it provides the ability to increment and decrement a cache key
atomatically.

```php
<?php

namespace Psr\SimpleCache;

interface CacheInterface
{
    const TTL_MINUTE = 60;
    const TTL_HOUR = 3600;
    const TTL_DAY = 86400;

    /**
     * Fetched a value from the cache.
     *
     * @param string $key The unique key of this item in the cache
     *
     * @return mixed The value of the item from the cache, or null in case of cache miss
     */
    public function get($key);

    /**
     * Persist data in the cache, uniquely referenced by a key with an optional expiration TTL time.
     *
     * @param string $key The key of the item to store
     * @param mixed $value The value of the item to store
     * @param null|integer|DateInterval $ttl Optional. The TTL value of this item. If no value is sent and the driver supports TTL
     *                                       then the library may set a default value for it or let the driver take care of that.
     *
     * @return bool True on success and false on failure
     */
    public function set($key, $value, $ttl = null);

    /**
     * Remove an item from the cache by its unique key
     *
     * @param string $key The unique cache key of the item to remove
     *
     * @return bool True on success and false on failure
     */
    public function remove($key);

    /**
     * Wipe clean the entire cache's keys
     *
     * @return bool True on success and false on failure
     */
    public function clear();

    /**
     * Obtain multiple cache items by their unique keys
     *
     * @param array|Traversable $keys A list of keys that can obtained in a single operation.
     *
     * @return array An array of key => value pairs. Cache keys that do not exist or are stale will have a value of null.
     */
    public function getMultiple($keys);

    /**
     * Persisting a set of key => value pairs in the cache, with an optional TTL.
     *
     * @param array|Traversable         $items An array of key => value pairs for a multiple-set operation.
     * @param null|integer|DateInterval $ttl   Optional. The amount of seconds from the current time that the item will exist in the cache for.
     *                                         If this is null then the cache backend will fall back to its own default behaviour.
     *
     * @return bool True on success and false on failure
     */
    public function setMultiple($items, $ttl = null);

    /**
     * Remove multiple cache items in a single operation
     *
     * @param array|Traversable $keys The array of string-based Keys to be removed
     *
     * @return bool True on success and false on failure
     */
    public function removeMultiple($keys);

    /**
     * Increment a value atomically in the cache by its step value, which defaults to 1
     *
     * @param string  $key  The cache item key
     * @param integer $step The value to increment by, defaulting to 1
     *
     * @return int|bool The new value on success and false on failure
     */
    public function increment($key, $step = 1);

    /**
     * Decrement a value atomically in the cache by its step value, which defaults to 1
     *
     * @param string  $key  The cache item key
     * @param integer $step The value to decrement by, defaulting to 1
     *
     * @return int|bool The new value on success and false on failure
     */
    public function decrement($key, $step = 1);
}
```


3. Adapter
----------

### 3.1 CacheAdapter

This PSR comes with an adapter class that can wrap a PSR-6 implementation
and expose it as a PSR-Simple-Cache object. This allows users and libraries
to easily rely on this even though cache libraries might only implement PSR-6.

Of course, cache implementations might down the line choose to implement
either or both PSRs.

Note that the adapter's increment & decrement methods are not strictly spec-compliant
as they can not be implemented atomically on top of PSR-6.

```php
namespace Psr\SimpleCache;

use Psr\Cache\CacheItemPoolInterface;

class CacheAdapter implements CacheInterface
{
    private $pool;

    public function __construct(CacheItemPoolInterface $pool)
    {
        $this->pool = $pool;
    }

    public function get($key)
    {
        $item = $this->pool->getItem($key);
        if ($item->isHit()) {
            return $item->get();
        }

        return null;
    }

    public function set($key, $value, $ttl = null)
    {
        $item = $this->pool->getItem($key)->set($value);
        if (null !== $ttl) {
            $item->expiresAfter($ttl);
        }

        return $this->pool->save($item);
    }

    public function remove($key)
    {
        return $this->pool->deleteItem($key);
    }

    public function clear()
    {
        return $this->pool->clear();
    }

    public function getMultiple($keys)
    {
        $result = array();
        foreach ($this->pool->getItems($keys) as $key => $item) {
            $result[$key] = $item->isHit() ? $item->get() : null;
        }

        return $result;
    }

    public function setMultiple($items, $ttl = null)
    {
        foreach ($items as $key => $value) {
            $item = $this->pool->getItem($key)->set($value);
            if (null !== $ttl) {
                $item->expiresAfter($ttl);
            }
            if (!$this->pool->saveDeferred($item)) {
                return false;
            }
        }

        return $this->pool->commit();
    }

    public function removeMultiple($keys)
    {
        return $this->pool->deleteItems($keys);
    }

    public function increment($key, $step = 1)
    {
        $value = $this->get($key) + $step;
        if ($this->set($key, $value)) {
            return $value;
        }

        return false;
    }

    public function decrement($key, $step = 1)
    {
        $value = $this->get($key) - $step;
        if ($this->set($key, $value)) {
            return $value;
        }

        return false;
    }
}
```
