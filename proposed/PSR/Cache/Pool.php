namespace PSR\Cache;

/**
 * Cache\Pool generates Cache\Item objects.
 */
interface Factory
{
    /**
     * Returns objects which implement the Cache\Item interface.
     *
     * Provided key must be unique for each item in the cache. Implementing
     * Libraries are responsible for any encoding or escaping required by their
     * backends, but must be able to supply the original key if needed. Keys
     * should be no longer than 1024 charactors and should not contain the
     * special charactors listed:
     *  {}()/\@
     *
     * @param string $key
     * @return PSR\Cache\Item
     */
    function getCache($key);

    /**
     * Returns a group of cache objects as a Cache\Iterator
     *
     * Bulk lookups can often by steamlined by backend cache systems. The
     * returned iterator will contain a Cache\Item for each key passed.
     *
     * @param array $key
     * @return PSR\Cache\Iterator
     */
    function getCacheIterator($keys);

    /**
     * Empties the cache pool of all items.
     *
     * @return bool
     */
    function empty();

}