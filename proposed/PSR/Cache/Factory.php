namespace PSR\Cache;

/**
 * CacheFactory generates CacheItem objects.
 */
interface CacheFactory 
{
    /**
     * Returns objects which implement the CacheItem interface.
     * 
     * @param string $key
     * @return CacheItem
     */
    function getCache($key);
}
