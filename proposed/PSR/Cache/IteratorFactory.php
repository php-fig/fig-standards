namespace PSR\Cache;

/**
 * Retrieves multiple items from the cache.
 *
 * CacheIteratorFactory allows multiple cache items to be retrieved at once and
 * returns them in a CacheIterator. This factory should also be able to return
 * cache objects on an individual basis using the CacheFactory interface.
 */
interface CacheIteratorFactory extends CacheFactory 
{
    /**
     *
     * @param array $key
     * @return CacheIterator
     */
    function getCacheIterator($keys);
}
