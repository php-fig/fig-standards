namespace PSR\Cache;

/**
 * Retrieves multiple items from the cache.
 *
 * CacheIteratorFactory allows multiple cache items to be retrieved at once and
 * returns them in a CacheIterator.
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
