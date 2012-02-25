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