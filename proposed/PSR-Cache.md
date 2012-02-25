##Introduction
##Goal
##Interface
###CacheFactory

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


###CacheItem

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

##Examples
##Extensions
###Namespaces
###Tags
###Drivers

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