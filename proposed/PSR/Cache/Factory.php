namespace PSR\Cache;

/**
 * Cache\Factory generates Cache\Item objects.
 */
interface Factory 
{
    /**
     * Returns objects which implement the Cache\Item interface.
     * 
     * @param string $key
     * @return PSR\Cache\Item
     */
    function getCache($key);
}
