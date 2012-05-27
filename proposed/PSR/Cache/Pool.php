namespace PSR\Cache;

/**
 * Cache\Pool generates Cache\Item objects.
 */
interface Pool
{
    /**
     * Returns objects which implement the Cache\Item interface.
     *
     * Provided key must be unique for each item in the cache. Implementing
     * Libraries are responsible for any encoding or escaping required by their
     * backends, but must be able to supply the original key if needed. Keys
     * should not contain the special characters listed:
     *  {}()/\@
     *
     * @param string $key
     * @return PSR\Cache\Item
     */
    function getCache($key);

    /**
     * Returns a group of cache objects as an \Iterator
     *
     * Bulk lookups can often by steamlined by backend cache systems. The
     * returned iterator will contain a Cache\Item for each key passed.
     *
     * @param array $keys
     * @return \Iterator
     */
    function getCacheIterator($keys);

    /**
     * Empties the cache pool of all items.
     *
     * @return bool
     */
    function flush();

}