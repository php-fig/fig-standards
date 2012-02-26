namespace PSR\Cache;

interface CacheIteratorFactory extends CacheFactory 
{

    /**
     *
     * @param array $key
     * @return CacheIterator
     */
    function getCacheIterator($keys);
}
