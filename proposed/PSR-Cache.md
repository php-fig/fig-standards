## Introduction

Caching is a common way to improve the performance of any project, making caching libraries one of the most common features of many frameworks and libraries. This has lead to a situation where many libraries roll their own caching libraries, with various levels of functionality, causing developers to have to learn multiple systems which may or may not provide the functionality they need. In addition, the developers of caching libraries themselves face a choice between only supporting a limited number of frameworks or creating a large number of adapter classes.

A common interface for caching systems will solve these problems. Library and framework developers can count on the caching systems working the way they're expecting, while the developers of caching systems will only have to implement a single set of interfaces rather than a whole assortment of adapters.


## Goal

The goal of this PSR is to allow developers to create cache-aware libraries that can be integrated into existing frameworks and systems without the need for custom development.


## Definitions

*    TTL - The Time To Live (TTL) of an item is the amount of time between when that item is stored and it is considered stale. The TTL is normally defined by an integer representing time in seconds, or a DateInterval object.  
 
*    Expiration - The actual time when an item is set to go stale. This it typically calculated by adding the TTL to the time when an object is stored, but can also be explicitly set with DateTime object.
    
    An item with a 300 second TTL stored at 1:30:00 will have an expiration at 1:35:00.
   
*    Key - A string that uniquely identifies the cached item. There are no restrictions on keys, with any escaping or normalizing occurring invisibly to the user by the implementing library.

*    Miss - An item is considered missing from the cache when it isn't there or has expired. Additional "miss" conditions can be defined by the implementing library, however the current ones can not be ignored (at no point should an expired item not be considered a miss).

*    Calling Library - The library that actually needs the cache services, this library will expect an object that fits the interfaces below but will have no knowledge of how they're actually implemented.

*    Implementing Library - Responsible for implementing the interface, this is the library that provides caching services to anyone who calls it.


## Data    

Acceptable data includes all PHP data types-

*    Strings - Simple, complex and large strings of any encoding.
*    Integers - Positive, negative and large integers (>32 bit).
*    Floats - Positive, negative and large.
*    Boolean- true, false.
*    Null
*    Arrays - indexed, associative and multidimensional.
*    Object - those that support the PHP serialize functionality.

All data passed into the Implementing Library must be returned exactly as passed. If this is not possible for whatever reason than it is preferable to respond with a cache miss than with corrupted data.


## Single Objects

### Factory

The main focus of the Cache\Factory object is to accept a key from the Calling Library and return the associated Cache\Item object. The majority of the Factory object's implementation is up to the Implementing Library, including all configuration, initialization and the injection itself into the Calling Library.


```php
namespace PSR\Cache;

/**
 * Cache\Factory generates Cache\Item objects.
 */
interface Factory
{
    /**
     * Returns objects which implement the Cache\Item interface.
     *
     * @param string $key
     * @return PSR\Cache\Item
     */
    function getCache($key);
}
```

### Item

The Cache\Item object encapsulates the storage and retrieval of cache items.

```php
namespace PSR\Cache;

/**
 * Cache\Item defines an interface for interacting with objects inside a cache.
 *
 * The Cache\Item interface defines an item inside a cache system, which can be
 * filled with any PHP value capable of being serialized. Each item Cache\Item
 * should be associated with a specific key, which can be set according to the
 * implementing system and is typically passed by the CacheFactory object.
 */
interface Item
{
    /**
     * Returns the key for the current cache item.
     *
     * The key is loaded by the implementing library, but should be available to
     * the higher level callers when needed. If no key is set false should be
     * returned.
     *
     *
     * @return string|false
     */
    function getKey();

    /**
     * Retrieves the item from the cache associated with this objects key.
     *
     * Value returned must be identical to the value original stored by set().
     *
     * If the cache is empty then null should be returned. However, null is also
     * a valid cache item, so the isMiss function should be used to check
     * validity.
     * 
     * @return mixed
     */
    function get();

    /**
     * Stores a value into the cache.
     *
     * The $value argument can be any item that can be serialized by PHP, although
     * the method of serialization is left up to the implementation.
     *
     * The $ttl can be defined in a number of ways. As an integer or
     * DateInverval object the argument defines how long before the cache should
     * expire. As a DateTime object the argument defines the actual expiration
     * time of the object. Implementations are allowed to use a lower time than
     * passed, but should not use a longer one.
     *
     * If no $ttl is passed then the item can be stored indefinitely or a
     * default value can be set by the implementation.
     *
     * Returns true if the item was successfully stored.
     *
     * @param mixed $value
     * @param int|DateInterval|DateTime $ttl
     * @return bool
     */
    function set($value, $ttl = null);

    /**
     * Validates the current state of the item in the cache.
     *
     * An item is considered a miss when it does not exist or has passed it's
     * expiration. Individual libraries or systems can define additional miss
     * conditions.
     *
     * @return bool
     */
    function isMiss();

    /**
     * Removes the current key from the cache.
     *
     * Returns true if the item was successfully removed.
     *
     * @return bool
     */
    function clear();
}
```


## Bulk Objects 
 
### IteratorFactory    

The Cache\IteratorFactory is an extension of the Cache\Factory class which is capable of performing bulk operations. While it can be used for retrieving individual items, it also defines a "getCacheIterator" function which takes an array of keys and returns a Cache\Iterator object.


```php
namespace PSR\Cache;

/**
 * Retrieves multiple items from the cache.
 *
 * IteratorFactory allows multiple cache items to be retrieved at once and
 * returns them in a Cache\Iterator. This factory should also be able to return
 * cache objects on an individual basis using the Cache\Factory interface.
 */
interface IteratorFactory extends Factory
{
    /**
     *
     * @param array $key
     * @return PSR\Cache\Iterator
     */
    function getCacheIterator($keys);
}
```


### Iterator

The Cache\Iterator is a collection of Cache\Item objects, typically returned by the Cache\IteratorFactory. This class allows Calling Libraries to iterate through each cache item, which allows developers to take actions based on the individual status of each item while still letting the Implementing Library utilize bulk operations where appropriate in the back end.


```php
namespace PSR\Cache;

/**
 * An iterator of Cache\Items.
 *
 * The Cache\Iterator provides a way to loop through Cache\Items, particularly
 * those returned by bulk operations.
 */
interface Iterator extends \Iterator
{

}
```

## Extensions

### Group Invalidation

#### Namespaces

#### Tags

#### Stacks




### Drivers

    namespace PSR\Cache;

    interface Driver
    {    
        /**
         *
         * @param array $key
         * @return array|false
         */        
        function retrieve($key);

        /**
         *
         * @param array $key
         * @param array $data
         * @param int $expiration
         * @return bool
         */        
        function store($key, $data, $ttl);

        /**
         *
         * @param null|array $key
         * @return bool
         */
        function clear($key = null);
    }