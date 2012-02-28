namespace PSR\Cache;

/**
 * Cache\Pool generates Cache\Item objects.
 */
interface Factory
{
    /**
     * Returns objects which implement the Cache\Item interface.
     *
     * 
     *
     * @param string $key
     * @return PSR\Cache\Item
     */
    function getCache($key);
    
    /**
     *
     * @param array $key
     * @return PSR\Cache\Iterator
     */
    function getCacheIterator($keys);    
}