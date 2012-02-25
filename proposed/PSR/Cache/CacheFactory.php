
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