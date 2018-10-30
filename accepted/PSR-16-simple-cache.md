Common Interface for Caching Libraries
======================================

This document describes a simple yet extensible interface for a cache item and
a cache driver.

The key words "MUST", "MUST NOT", "REQUIRED", "SHALL", "SHALL NOT", "SHOULD",
"SHOULD NOT", "RECOMMENDED", "MAY", and "OPTIONAL" in this document are to be
interpreted as described in [RFC 2119][].

The final implementations MAY decorate the objects with more
functionality than the one proposed but they MUST implement the indicated
interfaces/functionality first.

[RFC 2119]: http://tools.ietf.org/html/rfc2119

# 1. Specification

## 1.1 Introduction

Caching is a common way to improve the performance of any project, making
caching libraries one of the most common features of many frameworks and
libraries. Interoperability at this level means libraries can drop their
own caching implementations and easily rely on the one given to them by the
framework, or another dedicated cache library.

PSR-6 solves this problem already, but in a rather formal and verbose way for
what the most simple use cases need. This simpler approach aims to build a
standardized streamlined interface for common cases. It is independent of
PSR-6 but has been designed to make compatibility with PSR-6 as straightforward
as possible.

## 1.2 Definitions

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

* **Expiration** - The actual time when an item is set to go stale. This is
  calculated by adding the TTL to the time when an object is stored.

  An item with a 300 second TTL stored at 1:30:00 will have an expiration of 1:35:00.

  Implementing Libraries MAY expire an item before its requested Expiration Time,
  but MUST treat an item as expired once its Expiration Time is reached. If a calling
  library asks for an item to be saved but does not specify an expiration time, or
  specifies a null expiration time or TTL, an Implementing Library MAY use a configured
  default duration. If no default duration has been set, the Implementing Library
  MUST interpret that as a request to cache the item forever, or for as long as the
  underlying implementation supports.

  If a negative or zero TTL is provided, the item MUST be deleted from the cache
  if it exists, as it is expired already.

*    **Key** - A string of at least one character that uniquely identifies a
cached item. Implementing libraries MUST support keys consisting of the
characters `A-Z`, `a-z`, `0-9`, `_`, and `.` in any order in UTF-8 encoding and a
length of up to 64 characters. Implementing libraries MAY support additional
characters and encodings or longer lengths, but MUST support at least that
minimum. Libraries are responsible for their own escaping of key strings
as appropriate, but MUST be able to return the original unmodified key string.
The following characters are reserved for future extensions and MUST NOT be
supported by implementing libraries: `{}()/\@:`

*    **Cache** - An object that implements the `Psr\SimpleCache\CacheInterface` interface.

*    **Cache Misses** - A cache miss will return null and therefore detecting
if one stored `null` is not possible. This is the main deviation from PSR-6's
assumptions.

## 1.3 Cache

Implementations MAY provide a mechanism for a user to specify a default TTL
if one is not specified for a specific cache item. If no user-specified default
is provided implementations MUST default to the maximum legal value allowed by
the underlying implementation. If the underlying implementation does not
support TTL, the user-specified TTL MUST be silently ignored.

## 1.4 Data

Implementing libraries MUST support all serializable PHP data types, including:

*    **Strings** - Character strings of arbitrary size in any PHP-compatible encoding.
*    **Integers** - All integers of any size supported by PHP, up to 64-bit signed.
*    **Floats** - All signed floating point values.
*    **Booleans** - True and False.
*    **Null** - The null value (although it will not be distinguishable from a
cache miss when reading it back out).
*    **Arrays** - Indexed, associative and multidimensional arrays of arbitrary depth.
*    **Objects** - Any object that supports lossless serialization and
deserialization such that $o == unserialize(serialize($o)). Objects MAY
leverage PHP's Serializable interface, `__sleep()` or `__wakeup()` magic methods,
or similar language functionality if appropriate.

All data passed into the Implementing Library MUST be returned exactly as
passed. That includes the variable type. That is, it is an error to return
(string) 5 if (int) 5 was the value saved. Implementing Libraries MAY use PHP's
serialize()/unserialize() functions internally but are not required to do so.
Compatibility with them is simply used as a baseline for acceptable object values.

If it is not possible to return the exact saved value for any reason, implementing
libraries MUST respond with a cache miss rather than corrupted data.

# 2. Interfaces

## 2.1 CacheInterface

The cache interface defines the most basic operations on a collection of cache-entries, which
entails basic reading, writing and deleting individual cache items.

In addition, it has methods for dealing with multiple sets of cache entries such as writing, reading or
deleting multiple cache entries at a time. This is useful when you have lots of cache reads/writes
to perform, and lets you perform your operations in a single call to the cache server cutting down latency
times dramatically.

An instance of CacheInterface corresponds to a single collection of cache items with a single key namespace,
and is equivalent to a "Pool" in PSR-6. Different CacheInterface instances MAY be backed by the same
datastore, but MUST be logically independent.

~~~php
<?php

namespace Psr\SimpleCache;

interface CacheInterface
{
    /**
     * Fetches a value from the cache.
     *
     * @param string $key     The unique key of this item in the cache.
     * @param mixed  $default Default value to return if the key does not exist.
     *
     * @return mixed The value of the item from the cache, or $default in case of cache miss.
     *
     * @throws \Psr\SimpleCache\InvalidArgumentException
     *   MUST be thrown if the $key string is not a legal value.
     */
    public function get($key, $default = null);

    /**
     * Persists data in the cache, uniquely referenced by a key with an optional expiration TTL time.
     *
     * @param string                 $key   The key of the item to store.
     * @param mixed                  $value The value of the item to store. Must be serializable.
     * @param null|int|\DateInterval $ttl   Optional. The TTL value of this item. If no value is sent and
     *                                      the driver supports TTL then the library may set a default value
     *                                      for it or let the driver take care of that.
     *
     * @return bool True on success and false on failure.
     *
     * @throws \Psr\SimpleCache\InvalidArgumentException
     *   MUST be thrown if the $key string is not a legal value.
     */
    public function set($key, $value, $ttl = null);

    /**
     * Delete an item from the cache by its unique key.
     *
     * @param string $key The unique cache key of the item to delete.
     *
     * @return bool True if the item was successfully removed. False if there was an error.
     *
     * @throws \Psr\SimpleCache\InvalidArgumentException
     *   MUST be thrown if the $key string is not a legal value.
     */
    public function delete($key);

    /**
     * Wipes clean the entire cache's keys.
     *
     * @return bool True on success and false on failure.
     */
    public function clear();

    /**
     * Obtains multiple cache items by their unique keys.
     *
     * @param iterable $keys    A list of keys that can obtained in a single operation.
     * @param mixed    $default Default value to return for keys that do not exist.
     *
     * @return iterable A list of key => value pairs. Cache keys that do not exist or are stale will have $default as value.
     *
     * @throws \Psr\SimpleCache\InvalidArgumentException
     *   MUST be thrown if $keys is neither an array nor a Traversable,
     *   or if any of the $keys are not a legal value.
     */
    public function getMultiple($keys, $default = null);

    /**
     * Persists a set of key => value pairs in the cache, with an optional TTL.
     *
     * @param iterable               $values A list of key => value pairs for a multiple-set operation.
     * @param null|int|\DateInterval $ttl    Optional. The TTL value of this item. If no value is sent and
     *                                       the driver supports TTL then the library may set a default value
     *                                       for it or let the driver take care of that.
     *
     * @return bool True on success and false on failure.
     *
     * @throws \Psr\SimpleCache\InvalidArgumentException
     *   MUST be thrown if $values is neither an array nor a Traversable,
     *   or if any of the $values are not a legal value.
     */
    public function setMultiple($values, $ttl = null);

    /**
     * Deletes multiple cache items in a single operation.
     *
     * @param iterable $keys A list of string-based keys to be deleted.
     *
     * @return bool True if the items were successfully removed. False if there was an error.
     *
     * @throws \Psr\SimpleCache\InvalidArgumentException
     *   MUST be thrown if $keys is neither an array nor a Traversable,
     *   or if any of the $keys are not a legal value.
     */
    public function deleteMultiple($keys);

    /**
     * Determines whether an item is present in the cache.
     *
     * NOTE: It is recommended that has() is only to be used for cache warming type purposes
     * and not to be used within your live applications operations for get/set, as this method
     * is subject to a race condition where your has() will return true and immediately after,
     * another script can remove it, making the state of your app out of date.
     *
     * @param string $key The cache item key.
     *
     * @return bool
     *
     * @throws \Psr\SimpleCache\InvalidArgumentException
     *   MUST be thrown if the $key string is not a legal value.
     */
    public function has($key);
}
~~~

## 2.2 CacheException

~~~php

<?php

namespace Psr\SimpleCache;

/**
 * Interface used for all types of exceptions thrown by the implementing library.
 */
interface CacheException
{
}
~~~

## 2.3 InvalidArgumentException

~~~php
<?php

namespace Psr\SimpleCache;

/**
 * Exception interface for invalid cache arguments.
 *
 * When an invalid argument is passed, it must throw an exception which implements
 * this interface.
 */
interface InvalidArgumentException extends CacheException
{
}
~~~
