Common Interface for Caching libraries
================


This document describes a simple yet extensible interface for a cache item and
a cache driver.

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

In order to save the user information into a cache system, a simple cache
item is implemented which facilitates the storing of any and all possible
information that a user might want to store.

For this, the `Cache` implementation MUST be able to handle serialization
of PHP objects or MUST implement / execute a serialization / deserialization
of the cache item before saving/retrieving.

All the `TTL` references in this document are defined as the number of seconds
until the user of that value will be rendered invalid.

### 1.2 CacheItem

By `CacheItem` we refer to a object that implements the
`Psr\Cache\ItemInterface` interface.

Using the cache item approach to save the user that we can guarantee that
regardles of the `Cache` implementation, the user MUST always be able to
retrieve the same data he expects to retrieve / saved into `Cache`.

### 1.3 Cache

By `Cache` we refer to a object that implements the `Psr\Cache\CacheInterface`
interface.

When saving the cache item, it SHOULD perform only the save operation, but
other operations MAY be done such as logging / profiling. Other operation types
MAY be done as well but it is RECOMMENDED to keep the implementation as simple
as possible in order to it to be exchanged / used in other projects with ease.

If the user doesn't provide a TTL value then the `Cache` MUST set a default
value that is either configured by the user or, if not available, the maximum
value allowed by the driver.

It will be the implementation job to define what values are considered valid
or invalid for the specific driver but the user should be aware of the
accepted values by the underlying solution.

When saving new values into the cache system, the `Cache` implementation will
first create a `CacheItem` then store it. Users are allowed to create new
`CacheItem` objects but their usage is outside of this document scope.

`Cache` MUST return a `CacheItem` when the item is found in the cache and
`null` when the item is not found.

2. Package
----------

The interfaces and classes described as well as relevant exception classes
and a test suite to verify your implementation are provided as part of the
[php-fig/cache](https://packagist.org/packages/php-fig/psr-cache) package.

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
     * Set the value to be stored in the cache
     *
     * @param mixed $cacheValue
     *
     * @return null
     */
    public function setValue($cacheValue);

    /**
     * Get the value of the object
     *
     * @return mixed
     */
    public function getValue();

}

```

### 3.2 CacheInterface

```php

<?php

namespace Psr\Cache;

use Psr\Cache\ItemInterface;

/**
 * This is our cache driver
 */
interface CacheInterface
{

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
     * @param string   $key
     * @param mixed    $value
     * @param int|null $ttl
     *
     * @return boolean Result of the operation
     */
    public function set($key, $value, $ttl = null);

    /**
     * Remove a single cache entry
     *
     * @param string $key
     *
     * @return boolean Result of the operation
     */
    public function remove($key);

    /**
     * Set multiple entries in the cache
     *
     * @param ItemInterface[] $items
     * @param null|int $ttl
     */
    public function setMultiple(array $items, $ttl = null);

    /**
     * Get multiple entries the cache
     *
     * @param string[] $keys
     *
     * @return ItemInterface[]
     */
    public function getMultiple($keys);

    /**
     * Remove multiple entries from the cache
     *
     * @param string[] $keys
     */
    public function removeMultiple($keys);

    /**
     * Check if multiple entries exists in the cache
     *
     * @param string[] $keys
     *
     * @return boolean[]
     */
    public function existsMultiple($keys);

}

```
