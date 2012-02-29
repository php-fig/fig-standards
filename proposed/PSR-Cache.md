## Introduction

Caching is a common way to improve the performance of any project, making caching libraries one of the most common features of many frameworks and libraries. This has lead to a situation where many libraries roll their own caching libraries, with various levels of functionality, causing developers to have to learn multiple systems which may or may not provide the functionality they need. In addition, the developers of caching libraries themselves face a choice between only supporting a limited number of frameworks or creating a large number of adapter classes.

A common interface for caching systems will solve these problems. Library and framework developers can count on the caching systems working the way they're expecting, while the developers of caching systems will only have to implement a single set of interfaces rather than a whole assortment of adapters.


## Goal

The goal of this PSR is to allow developers to create cache-aware libraries that can be integrated into existing frameworks and systems without the need for custom development.


## Definitions

*    TTL - The Time To Live (TTL) of an item is the amount of time between when that item is stored and it is considered stale. The TTL is normally defined by an integer representing time in seconds, or a DateInterval object.  
 
*    Expiration - The actual time when an item is set to go stale. This it typically calculated by adding the TTL to the time when an object is stored, but can also be explicitly set with DateTime object.
    
    An item with a 300 second TTL stored at 1:30:00 will have an expiration at 1:35:00.
   
*    Key - A string that uniquely identifies the cached item. Implementing Libraries are responsible for any encoding or escaping requires by their backends, but must be able to supply the original key if needed. Keys should be no longer than 1024 characters and should not contain the special characters listed:

	{}()/\@

*    Miss - An item is considered missing from the cache when it isn't there or has expired. Additional "miss" conditions can be defined by the implementing library, however the current ones can not be ignored (at no point should an expired item not be considered a miss).

*    Calling Library - The library that actually needs the cache services, this library will expect an object that fits the interfaces below but will have no knowledge of how they're actually implemented.

*    Implementing Library - Responsible for implementing the interface, this is the library that provides caching services to anyone who calls it.


## Data    


Acceptable data includes all PHP data types-

*    Strings - Simple, complex and large strings of any encoding.
*    Integers - Positive, negative and large integers (>32 bit).
*    Floats - Positive, negative and large.
*    Boolean- true, false.
*    Null - not a wrapper or object, but the actual null value.
*    Arrays - indexed, associative and multidimensional.
*    Object - those that support the PHP serialize functionality.

All data passed into the Implementing Library must be returned exactly as passed. If this is not possible for whatever reason than it is preferable to respond with a cache miss than with corrupted data.


## Interfaces


### Cache\Pool

The main focus of the Cache\Pool object is to accept a key from the Calling Library and return the associated Cache\Item object. The majority of the Pool object's implementation is up to the Implementing Library, including all configuration, initialization and the injection itself into the Calling Library.

Items can be retrieved from the Cache\Pool either individually or as a group operation, either by using the getCache or getCacheIterator functions.

```php
namespace PSR\Cache;

/**
 * Cache\Pool generates Cache\Item objects.
 */
interface Pool
{
    /**
     * Returns objects which implement the Cache\Item interface.
     *
     * Provided key must be unique for each item in the cache. Implementing
     * Libraries are responsible for any encoding or escaping required by their
     * backends, but must be able to supply the original key if needed. Keys
     * should be no longer than 1024 characters and should not contain the
     * special characters listed:
     *  {}()/\@
     *
     * @param string $key
     * @return PSR\Cache\Item
     */
    function getCache($key);

    /**
     * Returns a group of cache objects as an \Iterator
     *
     * Bulk lookups can often by steamlined by backend cache systems. The
     * returned iterator will contain a Cache\Item for each key passed.
     *
     * @param array $key
     * @return \Iterator
     */
    function getCacheIterator($keys);

    /**
     * Empties the cache pool of all items.
     *
     * @return bool
     */
    function empty();

}
```


### Cache\Item

The Cache\Item object encapsulates the storage and retrieval of cache items. Each Cache\Item is already associated with a Key (how is the responsibility of the Implementing Library).

```php
namespace PSR\Cache;

/**
 * Cache\Item defines an interface for interacting with objects inside a cache.
 *
 * The Cache\Item interface defines an item inside a cache system, which can be
 * filled with any PHP value capable of being serialized. Each item Cache\Item
 * should be associated with a specific key, which can be set according to the
 * implementing system and is typically passed by the Cache\Pool object.
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
     * Returns true if the item is no longer present (either because it was
     * removed or was not present to begin with).
     *
     * @return bool
     */
    function remove();
}
```


## Extensions

Extensions are optional which do not need to be implemented by the Implementing Library but which may provide useful functionality or insights. Calling Libraries should not rely on any of the functionality below, but can use any relevant interfaces. These extensions primarily exist to show how the existing standard can be extending by interested developers while still meeting the guidelines of this standard.


### Namespaces

Namespaces can be used to seperate the storage of different systems in the cache. This allows different sections to be cleared on an individual level, while also preventing overlapping keys.

Supporting namespaces is out of the scope of this standard, but can easily be accomplished by the Implementing Library as part of the Cache\Factory. Different Cache\Factory objects can be assigned namespaces and then get injected into their respective Calling Libraries, and those libraries will not need to treat them any differently.


### Stacks

Stacks are a special kind of grouping system that allow cache items to be nested, similar to how folders are nested in filesystems. Stacks work by adding a special charactor to Keys, the slash, which tells the Implementing Library where the nesting points out. If no nesting is used, Stacks behave exactly like the standard Cache interfaces.

> An example key may look like "Users/Bob/Friends", "Users/Bob/Friends/Active" or just "Users/Bob". The special thing about Stacks is that clearing out "Users/Bob" also clears out "Users/Bob/Friends" and "Users/Bob/Friends/Active". 


#### StackablePool

```php
namespace PSR\Cache\Extensions;

/**
 * Cache\Extensions\StackablePool extends Cache\Pool to provide stacking support.
 *
 * The Cache\Extensions\StackablePool interface adds support for returning
 * Cache\Extensions\StackableItem objects. This works primarily by defining the
 * Key to use a slash as a delimiter, similar to a filesystem, to nest keys.
 * When a StackableItem is cleared it also clears the items nested beneath it.
 */
interface StackablePool extends \PSR\Cache\Pool
{

}
```


#### StackableItem

```php
namespace PSR\Cache\Extensions;

/**
 * Cache\Extensions\StackableItem extends Cache\Item to provide stacking support.
 *
 * The Cache\Extensions\StackableItem interface adds support for stacking to the
 * base Cache\Item interface. When a StackableItem is cleared it also clears the
 * items nested beneath it.
 */
interface StackableItem extends \PSR\Cache\Item
{

}

```


### Tags

Tagging interfaces are provided for completeness, but developers should note the difficulty in providing a consistant high performance tagging solution.


#### Cache\Extensions\TaggablePool

```php
namespace PSR\Cache\Extensions;

/**
 * Cache\Extensions\TaggablePool extends Cache\Pool to provide tagging support.
 *
 * The Cache\Extensions\TaggablePool interface adds support for returning
 * Cache\Extensions\TaggbleItem objects, as well as clearing the pool of tagged
 * Items.
 */
interface TaggablePool extends \PSR\Cache\Pool
{
    /**
     * Clears the cache of all items with the specified tag.
     *
     * @param string
     * @return bool
     */
    function clearByTag($tag);
}
```


#### Cache\Extensions\TaggableItem

```php
namespace PSR\Cache\Extensions;

/**
 * Cache\Extensions\TaggableItem extends Cache\Item to provide tagging support.
 *
 * The Cache\Extensions\TaggableItem interface adds support for tagging to the
 * base Cache\Item interface. Items can be added to multiple categories (called
 * tags) that can be used for group invalidation.
 */
interface TaggableItem extends \PSR\Cache\Item
{
    /**
     * Sets the tags for the current item.
     *
     * Accepts an array of strings for the item to be tagged with. The tags
     * passed should overwrite any existing tags, and passing an empty array
     * will cause all tags to be removed. Changes to an Item's tags are not
     * guaranteed to persist unless the "set" function is called.
     *
     * @return void
     */
    function setTags(array $tags = array());
}

```