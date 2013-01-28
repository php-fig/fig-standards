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

[RFC 2119]: http://tools.ietf.org/html/rfc2119

1. Specification
-----------------

### 1.1 Quick description

In order to save the user information into a cache system, a simple cache item
is implemented which facilitates the storing of any and all possible
information that a user might want to store.

For this, the `Cache` implementation MUST be able to handle any values that
a user could store, including and not limited to PHP objects, null values,
boolean values and so on.

All the `TTL` references in this document are defined as the number of seconds
until the item using it will be rendered invalid/expired/deleted from the
caching system.

### 1.2 CacheItem

By `CacheItem` we refer to a object that implements the
`Psr\Cache\CacheItemInterface` interface.

By using the cache item implementations will guarantee consistency across
various systems and ensure that the user will always retrieve the expected data
without performing any additional operations.

### 1.3 Cache

By `Cache` we refer to a object that implements the `Psr\Cache\CacheInterface`
interface.

If the user does not provide a TTL value then the `Cache` MUST set a default
value that is either configured by the user or, if not available, the maximum
value allowed by cache system.

It will be the implementation job to define what values are considered valid
or invalid for the specific storage but the user MUST be aware of the accepted
values by the underlying solution both for TTL values as well as for key names.

`Cache` MUST return always a `CacheItem` when the item is found in the cache
and `null` when the item is not found.

For ```setMultiple``` the array MUST be associative where the pair key/value
will represent the key and value of the item in to be stored in the caching
engine.

2. Interfaces
----------

### 2.1 CacheItemInterface

```php

<?php

namespace Psr\Cache;

/**
 * Interface for caching object
 */
interface CacheItemInterface
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

### 2.2 CacheInterface

```php

<?php

namespace Psr\Cache;

use Psr\Cache\CacheItemInterface;

/**
 * This is our cache driver
 */
interface CacheInterface
{

    /**
     * Set default TTL value
     *
     * @param int $ttl
     */
    public function setDefaultTtl($ttl);

    /**
     * Get cache entry
     *
     * @param string $key
     *
     * @return CacheItemInterface|null
     */
    public function get($key);

    /**
     * Check if a cache entry exists
     *
     * @param string $key
     *
     * @return Boolean
     */
    public function exists($key);

    /**
     * Set a single cache entry
     *
     * @param string   $key
     * @param mixed    $value
     * @param int|null $ttl
     *
     * @return Boolean Result of the operation
     */
    public function set($key, $value, $ttl = null);

    /**
     * Remove a single cache entry
     *
     * @param string $key
     *
     * @return Boolean Result of the operation
     */
    public function remove($key);

    /**
     * Get multiple entries the cache
     *
     * @param string[] $keys
     *
     * @return CacheItemInterface[]
     */
    public function getMultiple($keys);

    /**
     * Check if multiple entries exists in the cache
     *
     * @param string[] $keys
     *
     * @return Boolean[]
     */
    public function existsMultiple($keys);

    /**
     * Set multiple entries in the cache
     *
     * @param array    $items
     * @param null|int $ttl
     */
    public function setMultiple(array $items, $ttl = null);

    /**
     * Remove multiple entries from the cache
     *
     * @param string[] $keys
     */
    public function removeMultiple($keys);

    /**
     * This allows to clear (flush) all the cache contents
     *
     * return Boolean
     */
    public function flush();

}

```


3. Package
----------

The interfaces described as well as a test suite to verify your implementation
are provided as part of the [php-fig/cache](https://packagist.org/packages/php-fig/psr-cache) package.

4. Appendix
----------

### 4.1 Usage of CacheItem

Since various cache systems / or drivers are not fully capable of storing all
the nativ data types present in PHP as well as have a consistent return value
in case the stored value wasn't found that would not conflict otherwise with
the value stored by the user, the CacheItem approach was used.

This helps implementations store any data type in the cache system then allows
each implementation do deal with the mentioned shortcomings.

The setter method is present so that insures interoperability across various
libraries and to provide a common method of setting the value of the returned
object.

Due to the resons behind usage of cache item, the usage of such object outside
of retrieving of information from the cache system.


### 4.2 CacheInterface

The method ```exists()``` is present in order to facilitate cases where a user
will want to just check for an item presence rather that fetching it in case it
exists.
