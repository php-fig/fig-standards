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

All the `TTL` references in this document are defined as the number of seconds
until the user of that value will be rendered invalid.

### 1.2 CacheItem

By `CacheItem` we refer to a object that implements the
`Psr\Cache\ItemInterface` interface.

Using the cache item approach to save the user that we can guarantee that
regardles of the driver implementation, the user MUST always be able to
retrieve the same data he expects to retrieve / saved into the driver.

The cache item MUST also contain a function that allows the user to retrieve
the remaining TTL of the item in order to better coordinate with its expiry
time. In order to provide this functionality, the `CacheItem` SHOULD store
the timestamp for the save time of the item in the cache so that it can then
compute the remaining TTL.

### 1.3 Cache

By `CacheDriver` we refer to a object that implements the
`Psr\Cache\CacheInterface` interface.

When saving the cache item the driver SHOULD perform the save operation only
but other operations MAY be done such as logging / profiling. Other operation
types are could be done as well but it is RECOMMENDED to keep the driver as
simple as possible in order to it to be exchanged / used in other projects
with ease.

The `defaultTTL` value MUST be expressed in seconds and will be used when the
user will not provide a TTL to the value that's saved to the cache system.
It will also serve as point of reference when the value is not valid for the
used driver as well as a default value when the `TTL` or `remainingTTL` are
not present in the `CacheItem` or are damaged.

It will be the implementation job to define what values are considered valid
or invalid for the specific driver but the user should be aware of the
accepted values by the underlying solution.

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

}

```

### 3.2 CacheInterface

```php

<?php

namespace Psr\Cache;

use Psr\Cache\ItemInterface;

/**
 * This is our cache proxy
 */
interface CacheInterface
{

    /**
     * Get the default TTL of the instance in seconds
     *
     * @return int
     */
    public function getDefaultTtl();

    /**
     * Set the default TTL of the instance
     *
     * @param $defaultTtl
     *
     * @return CacheProxyInterface
     */
    public function setDefaultTtl($defaultTtl);

    /**
     * Get cache entry
     *
     * @param string $key
     *
     * @return ItemInterface|null
     */
    public function get($key);

    /**
     * Check if a cache entry exists
     *
     * @param string $key
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
     * @param string $key
     *
     * @return boolean Result of the operation
     */
    public function remove($key);

    /**
     * Set multiple keys in the cache
     * If $ttl is not passed then the default TTL is used
     *
     * @param ItemInterface[] $items
     * @param null|int $ttl
     */
    public function setMultiple(array $items, $ttl = null);

    /**
     * Get multiple keys the cache
     *
     * @param string[] $keys
     *
     * @return ItemInterface[]
     */
    public function getMultiple($keys);

    /**
     * Remove multiple keys from the cache
     *
     * @param string[] $keys
     */
    public function removeMultiple($keys);

    /**
     * Check if multiple keys exists in the cache
     *
     * @param string[] $keys
     *
     * @return boolean[]
     */
    public function existsMultiple($keys);

}

```
