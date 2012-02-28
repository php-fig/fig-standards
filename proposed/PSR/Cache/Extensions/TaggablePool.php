namespace PSR\Cache\Extensions;

/**
 * Cache\Extensions\TaggablePool extends Cache\Pool to provide tagging support.
 *
 * The Cache\Extensions\TaggablePool interface adds support for returning
 * Cache\ExtensionsTaggbleItem objects, as well as clearing the pool of tagged
 * Items. 
 */
interface TaggablePool extends \PSR\Cache\Pool
{
    /**
     * Clears the cache of all items with the specified tag.
     *
     * @param string
     * @return bool
     */
    function clearByTag($tag);
}