Common Interface for Caching libraries
================


This document describes a simple yet extensible interface for a cache item,
a cache driver and a cache proxy.

The key words "MUST", "MUST NOT", "REQUIRED", "SHALL", "SHALL NOT", "SHOULD",
"SHOULD NOT", "RECOMMENDED", "MAY", and "OPTIONAL" in this document are to be
interpreted as described in [RFC 2119][].

The final implementations MAY be able to decorate the objects with more
functionality that the one proposed but they MUST implement the indicated
interfaces/functionality first.

Also, since this involves caching, which is used to obtain better performance
from systems, the implementation detail is RECOMMENDED to be as simple and
fast as possible.

For this reason, this document doesn't describe how tags, namespaces or
locking problems are addressed as they are OPTIONAL and MAY be implemented by
each vendor as it sees fit for the specific needs. One of the main reason is
the lack of support into many existing, at time of this document creation,
fast key/value storages as well as potential performance issues due to
improper implementations.

[RFC 2119]: http://tools.ietf.org/html/rfc2119

1. Specification
-----------------

### 1.1 Quick description

In order to save the user information into the cache driver, a simple cache
item is implemented which facilitates the storing of any and all possible
information that a user might want to store.

For this, the cache driver implementation MUST specify if it's able to handle
native serialization of PHP objects or the cache proxy MUST implement / execute
a serialization/deserialization of the cache item before saving/retrieving.

The cache proxy MUST receive a cache driver which implements
`Psr\Cache\DriverInterface` and must receive a `Psr\Cache\ItemInterface`
in order to save it in to the cache driver.

### 1.2 CacheItem

By `CacheItem` we refer to a object that implements the
`Psr\Cache\ItemInterface` interface.

Using the cache item approach to save the user that we can guarantee that
regardles of the driver implementation, the user MUST always be able to
retrieve the same data he expects to retrieve / saved into the driver.

The item MUST store the user value as well as additional metadata for it.

The cache item should also contain a function that allows the user to retrieve
the remaining TTL of the item in order to better coordinate with its expiry
time.

### 1.3 CacheDriver

By `CacheDriver` we refer to a object that implements the
`Psr\Cache\DriverInterface` or `Psr\Cache\BatchDriverInterface` interface.

A driver MUST to be decoupled from the proxy so that it only implements the
basic operations described in either the `DriverInterface` or in
`BatchDriverInterface` which extends the first one.

It is the cache proxy MUST serialize the information in the right way if the
driver doesn't support for serialization.

When saving the cache item the driver SHOULD perform the save operation only
but other operations MAY be done such as logging / profiling. Other operation
types are could be done as well but it is RECOMMENDED to keep the driver as
simple as possible in order to it to be exchanged / used in other projects
with ease.

In case the driver does not implement `BatchDriverInterface` then the
`CacheProxy` MUST implement the missing functionality so that the end-user has
the same consistent behavior.

If the cache driver provides only some support for multiple (bulk) operations
then the driver MUST implement the rest of the missing operations instead of
the `CacheProxy` in order to keep the driver consistent with the specified
interface.

The same goes for the drivers that have support only for multiple operations
and have no or partial support for single operations.

### 1.4 CacheProxy

By `CacheProxy` we refer to a object that implements the
`Psr\Cache\CacheProxyInterface` interface.

The `CacheProxy` MUST provide the functionality to perform the whole range of
operations described in the `DriverInterface` as well as those from
the `BatchDriverInterface`.

Cache proxy MUST send the right data to the drivers, be it in the form of
serialized `CacheItem` or directly as a `CacheItem` object, depending on the
capabilities of the used driver.

2. Package
----------

The interfaces and classes described as well as relevant exception classes
and a test suite to verify your implementation are provided as part of the
[php-fig/Psr-cache](https://packagist.org/packages/php-fig/psr-cache) package.

3. Interfaces
----------

### 3.1 ItemInterface

```php

<?php

namespace Psr\Cache;

/**
 * Interface for caching object
 */
interface ItemInterface
{
    /**
     * Set the value of the key to store our value under
     *
     * @param string $cacheKey
     *
     * @return ItemInterface
     */
    public function setKey($cacheKey);

    /**
     * Get the key of the object
     *
     * @return string
     */
    public function getKey();

    /**
     * Set the value to be stored in the cache
     *
     * @param mixed $cacheValue
     *
     * @return ItemInterface
     */
    public function setValue($cacheValue);

    /**
     * Get the value of the object
     *
     * @return mixed
     */
    public function getValue();

    /**
     * Set the TTL value
     *
     * @param int $ttl
     *
     * @return ItemInterface
     */
    public function setTtl($ttl);

    /**
     * Get the TTL of the object
     *
     * @return int
     */
    public function getTtl();

    /**
     * Get the remaining time in seconds until the item will expire
     * The implementation should save the expiry time in the item metadata on
     * save event and then retrieve it from the object metadata and substract
     * it from the current time
     *
     * *Note* certain delays can occur as the save event won't be able to
     * provide actual save time of when the user called the save method and
     * the real save time when the driver will save the item
     *
     * @return int
     */
    public function getRemainingTtl();

    /**
     * Set a metadata value
     *
     * @param string $key
     * @param mixed $value
     *
     * @return ItemInterface
     */
    public function setMetadata($key, $value);

    /**
     * Do we have any metadata with the object
     *
     * @param string|null $key
     *
     * @return boolean
     */
    public function hasMetadata($key = null);

    /**
     * Get parameter/key from the metadata
     *
     * @param string|null $key
     *
     * @return mixed
     */
    public function getMetadata($key = null);

}

```

### 3.2 DriverInterface

```php

<?php

namespace Psr\Cache;

/**
 * Interface for cache drivers
 */
interface DriverInterface
{
    /**
     * Set data into cache.
     *
     * @param string $key      Entry id
     * @param mixed  $value    Cache entry
     * @param int    $lifeTime Life time of the cache entry
     *
     * @return boolean
     */
    public function set($key, $value, $lifeTime = 0);

    /**
     * Check if an entry exists in cache
     *
     * @param string $key Entry id
     *
     * @return boolean
     */
    public function exists($key);

    /**
     * Get an entry from the cache
     *
     * @param string $key Entry id
     * @param boolean|null $exists If the operation was succesfull or not
     *
     * @return mixed The cached data or FALSE
     */
    public function get($key, &$exists = null);

    /**
     * Removes a cache entry
     *
     * @param string $key Entry id
     *
     * @return boolean
     */
    public function remove($key);

    /**
     * If this driver has support for serialization or not
     *
     * @return boolean
     */
    public function hasSerializationSupport();

}

```

### 3.3 BatchDriverInterface

```php

<?php

namespace Psr\Cache;

/**
 * Interface for cache drivers that can support multiple operations at once
 */
interface BatchDriverInterface extends DriverInterface
{

    /**
     * Stores multiple items in the cache at once.
     *
     * The items must be provided as an associative array.
     *
     * @param array $items
     * @param int   $ttl
     *
     * @return boolean[]
     */
    public function setMultiple(array $items, $ttl = 0);

    /**
     * Fetches multiple items from the cache.
     *
     * The returned structure must be an associative array. If items were
     * not found in the cache, they should not be included in the array.
     *
     * This means that if none of the items are found, this method must
     * return an empty array.
     *
     * @param array $keys
     *
     * @return array
     */
    public function getMultiple(array $keys);

    /**
     * Deletes multiple items from the cache at once.
     *
     * @param array $keys
     *
     * @return boolean[]
     */
    public function removeMultiple(array $keys);

    /**
     * Check for multiple items if they appear in the cache.
     *
     * All items must be returned as an array. And each must array value
     * must either be set to true, or false.
     *
     * @param array $keys
     *
     * @return array
     */
    public function existsMultiple(array $keys);

    /**
     * If this driver has support for serialization or not
     *
     * @return boolean
     */
    public function hasSerializationSupport();

}

```

### 3.4 CacheProxyInterface

```php

<?php

namespace Psr\Cache;

use Psr\Cache\ItemInterface;
use Psr\Cache\DriverInterface;

/**
 * This is our cache proxy
 */
interface CacheProxyInterface
{

    /**
     * Create the proxy that's going to be used by the end-user by adding the
     *
     * @param DriverInterface $cacheDriver
     */
    public function __construct(DriverInterface $cacheDriver);

    /**
     * Get the default TTL of the instance
     *
     * @return int
     */
    public function getDefaultTtl();

    /**
     * Set the default TTL of the instance
     *
     * @param $defaultTtl
     *
     * @return Driver
     */
    public function setDefaultTtl($defaultTtl);

    /**
     * Get cache entry
     *
     * @param string|ItemInterface $key
     * @param boolean|null $exists
     *
     * @return ItemInterface
     */
    public function get($key, &$exists = null);

    /**
     * Check if a cache entry exists
     *
     * @param string|ItemInterface $key
     *
     * @return boolean
     */
    public function exists($key);

    /**
     * Set a single cache entry
     *
     * @param ItemInterface $cacheItem
     *
     * @return boolean Result of the operation
     */
    public function set(ItemInterface $cacheItem);

    /**
     * Remove a single cache entry
     *
     * @param string|ItemInterface $key
     *
     * @return boolean Result of the operation
     */
    public function remove($key);

    /**
     * Set multiple keys in the cache
     * If $ttl is not passed then the default TTL for this driver will be used
     *
     * @param string[]|ItemInterface[]|mixed[] $items
     * @param null|int $ttl
     */
    public function setMultiple(array $items, $ttl = null);

    /**
     * Get multiple keys the cache
     *
     * @param string[]|ItemInterface[]|mixed[] $keys
     *
     * @return ItemInterface[]
     */
    public function getMultiple($keys);

    /**
     * Remove multiple keys from the cache
     *
     * @param string[]|ItemInterface[]|mixed[] $keys
     */
    public function removeMultiple($keys);

    /**
     * Check if multiple keys exists in the cache
     *
     * @param string[]|ItemInterface[]|mixed[] $keys
     *
     * @return boolean[]
     */
    public function existsMultiple($keys);

}

```