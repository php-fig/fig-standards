Cache Interface
===============

Many PHP frameworks define a PHP persistent object cache with various features.
An object cache is something that may be useful in many parts of an
application, and thus may be injected in quite a bit of subsystems.

Many existing frameworks, libraries and applications redefine these systems,
often including APC, Memcached and many others.

This document proposes a very simple standard interface. It is meant to cover
*just* the base functionality most cache libraries provide.

We realize that this interface will not cover every usecase, nor do we expect
this to be the case. We hope for the cache providers that already have an
existing implementation and wish to not break backwards compatibility, an
adapter can be provided for compatibility with this standard.

1. The base interface
---------------------

```php
namespace PSR\Cache;

interface Base
{

    /**
     * Stores a new value in the cache.
     *
     * The key must be provided as a string, the value may be any
     * serializable PHP value.
     *
     * Use the $ttl argument to specify how long the cache is valid for.
     * The time-to-live is specified in seconds.
     *
     * If $ttl is not specified, the implementation may choose a default.
     * The $ttl argument should be considered a 'suggestion'. The
     * implementation may ignore it.
     *
     * @param string $key
     * @param mixed $value
     * @param int $ttl
     * @return void
     */
    public function set($key, $value, $ttl = null);

    /**
     * Fetches an object from the cache.
     *
     * If the object did not exist, this method must return null.
     *
     * @param string $key
     * @return mixed
     */
    public function get($key);

    /**
     * Deletes an item from the cache.
     *
     * This method must succeed, even if the item did not exist.
     *
     * @param string $key
     * @return void
     */
    public function delete($key);


    /**
     * Check if the key exists in the cache.
     *
     * @param string $key
     * @return boolean
     */
    public function exists($key);

}
```

2. Bulk Operations
------------------

For some cases it's beneficial to request multiple objects at once. One big
benefit is that requests can be pipelined, thus reducing latency.

For these cases the Multiple interface may be implemented.

```php
namespace PSR\Cache;

interface Multiple extends Base
{

    /**
     * Stores multiple items in the cache at once.
     *
     * The items must be provided as an associative array.
     *
     * @param array $items
     * @param int $ttl
     * @return void
     */
    public function setMultiple(array $items, $ttl = null);

    /**
     * Fetches multiple items from the cache.
     *
     * The returned structure must be an associative array. If items were
     * not found in the cache, they should not be included in the array.
     *
     * This means that if none of the items are found, this method must
     * return an empty array.
     *
     * @param string $keys
     * @return array
     */
    public function getMultiple($keys);

    /**
     * Deletes multiple items from the cache at once.
     *
     * @param array $key
     * @return void
     */
    public function deleteMultiple($keys);

    /**
     * Check for multiple items if they appear in the cache.
     *
     * All items must be returned as an array. And each must array value
     * must either be set to true, or false.
     *
     * @param array $keys
     * @return array
     */
    public function existsMultiple($keys);

}
```

If the backend does not natively implement bulk operations, it can still
be easily emulated. The following trait could be used as an example to emulate
this. 

**Note that this trait is strictly an example, and MUST NOT be considered as
part of the standard.**

```php

trait EmulateMultiple
{

    /**
     * Stores multiple items in the cache at once.
     *
     * The items must be provided as an associative array.
     *
     * @param array $items
     * @param int $ttl
     * @return void
     */
    public function setMultiple(array $items, $ttl = null)
    {

        foreach($items as $key=>$value) {
            $this->set($key, $value, $ttl);
        }

    }

    /**
     * Fetches multiple items from the cache.
     *
     * The returned structure must be an associative array. If items were
     * not found in the cache, they should not be included in the array.
     *
     * This means that if none of the items are found, this method must
     * return an empty array.
     *
     * @param string $keys
     * @return array
     */
    public function getMultiple($keys)
    {

        return array_map(
            array($this, 'get'),
            $keys
        );

    }

    /**
     * Deletes multiple items from the cache at once.
     *
     * @param array $key
     * @return void
     */
    public function deleteMultiple($keys)
    {

        foreach($keys as $key) {
            $this->delete($key);
        }

    }

    /**
     * Check for multiple items if they appear in the cache.
     *
     * All items must be returned as an array. And each must array value
     * must either be set to true, or false.
     *
     * @param array $keys
     * @return array
     */
    public function existsMultiple($keys)
    {

        return array_map(
            array($this, 'exists'),
            $keys
        );

    }

}
```

3. Notes
--------

* This document does not define how to handle error conditions, such as the
  inability to store an item, due to for example a backend being down.
* This document does not define what constitutes valid keys. Backends may have
  restrictions around this. It is recommended for the calling code to use
  sane keys, and possibly run a hash function if needed.
