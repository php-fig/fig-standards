## Introduction


## Goal

The goal of this PSR is to allow developers to create cache-aware libraries that can be integrated into existing frameworks and systems without the need for custom development.



## Standard

### Definitions

* Data - 
* TTL - 
* Expiration - 
* Key - 
* Miss - 


### Interfaces

#### CacheFactory

    namespace PSR\Cache;

    interface CacheFactory 
    {

        /**
         *
         * @param string $key
         * @return CacheItem
         */
        function getCache($key);
    }


#### CacheItem

    namespace PSR\Cache;

    interface CacheItem 
    {
        /**
         *
         * @return mixed
         */        
        function get();

        /**
         *
         * @param mixed $value
         * @param int $ttl
         * @return bool
         */        
        function set($value, $ttl = null);

        /**
         *
         * @return bool
         */
        function isMiss();

        /**
         *
         * @return mixed
         */
        function clear();
    }
    

## Examples

## Extensions


### Multiple

#### CacheIteratorFactory    
    
    namespace PSR\Cache;

    interface CacheIteratorFactory extends CacheFactory 
    {

        /**
         *
         * @param array $key
         * @return CacheIterator
         */
        function getCacheIterator($keys);
    }    
        
#### CacheIterator

    namespace PSR\Cache;
    
    interface CacheIterator extends \Iterator
    {
    
    }


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