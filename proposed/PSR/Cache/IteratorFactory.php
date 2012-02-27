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