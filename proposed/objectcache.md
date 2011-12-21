## Introduction

Many PHP frameworks define a PHP persistant object cache with various features.
An object cache is something that may be useful in many parts of an
application, and thus may be injected in quite a bit of subsystems.

Examples of this can be found in the Doctrine source as
Doctrine\_Cache\_Interface, and in The Zend Framework source as
\Zend\Cache\Backend.

Both of these then implement their respective interfaces for various types
of common storage systems, including APC shared memory, memcached and the
filesystem.

When a user wants to use multiple frameworks, they are stuck configuring
multiple systems. In addition, library writers that wish to allow users to
leverage an existing caching layer, are stuck providing yet another
implementation, or providing a range of adapters for each of the frameworks
they wish to support.

To fix this, this document proposes a very simple standard interface.

## The base interface

    namespace PSR\Cache;

    interface Base {

        /**
         * Stores a new value in the cache.
         *
         * The key must be provided as a string, the value may be any serializable
         * PHP value.
         *
         * The $ttl represents the time the cache entry is valid for. This value
         * should be treated as advisory, and may be ignored by implementations
         *
         * @param string $key
         * @param mixed $value
         * @param int $ttl
         * @return void
         */
        function set($key, $value, $ttl = null);

        /**
         * Fetches an object from the cache.
         *
         * If the object did not exist, this method must return null.
         *
         * @param string $key
         * @return mixed
         */
        function get($key);

        /**
         * Deletes an item from the cache.
         *
         * This method must succeed, even if the item did not exist.
         *
         * @param string $key
         * @return void
         */
        function delete($key);
        
        
        /**
         * Check if the key exists in the cache.
         *
         * @param string $key
         * @return boolean
         */
        function exists($key);

        /**
         * Clears the entire cache.
         *
         * Implementations may choose to ignore this. What happens in this case
         * is up to the implementor.
         *
         * @return void
         */
        function clear();

    }

## Bulk operations

For some cases it's beneficial to request multiple objects at once. One big
benefit is that requests can be pipelined, thus reducing latency. 

For these cases the Multiple interface may be implemented.

    namespace PSR\Cache;

    interface Multiple extends Base {

        /**
         * Stores multiple items in the cache at once.
         *
         * The items must be provided as an associative array. 
         *
         * The $ttl represents the time the cache entry is valid for. This value
         * should be treated as advisory, and may be ignored by implementations
         *
         * @param array $items
         * @param int $ttl
         * @return void
         */
        function setMultiple(array $items, $ttl = null);

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
        function getMultiple($keys);

        /**
         * Deletes multiple items from the cache at once.
         *
         * @param array $key
         * @return void 
         */
        function deleteMultiple($keys);

        /**
         * Check for multiple items if they appear in the cache.
         *
         * All items must be returned as an array. And each must array value
         * must either be set to true, or false.
         *
         * @param array $keys
         * @return array
         */
        function existsMultiple($keys);

    }

If the backend does not natively implement bulk operations, it can still
be easily emulated. The following trait may serve as an example:

trait 


## Notes

* This document does not define how to handle error conditions, such as the
  inability to store an item, due to for example a backend being down.
* This document does not define what constitutes valid keys. Backends may have
  restrictions around this. It is recommended for the calling code to use
  sane keys, and possibly run a hash function if needed.

## Todo


