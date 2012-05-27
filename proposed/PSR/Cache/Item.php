namespace PSR\Cache;

/**
 * Cache\Item defines an interface for interacting with objects inside a cache.
 *
 * The Cache\Item interface defines an item inside a cache system, which can be
 * filled with any PHP value capable of being serialized. Each item Cache\Item
 * should be associated with a specific key, which can be set according to the
 * implementing system and is typically passed by the Cache\Pool object.
 */
interface Item
{
    /**
     * Returns the key for the current cache item.
     *
     * The key is loaded by the Implementing Library, but should be available to
     * the higher level callers when needed.
     *
     * @return string|false
     */
    function getKey();

    /**
     * Retrieves the item from the cache associated with this objects key.
     *
     * Value returned must be identical to the value original stored by set().
     *
     * If the cache is empty then null should be returned. However, null is also
     * a valid cache item, so the isMiss function should be used to check
     * validity.
     *
     * @return mixed
     */
    function get();

    /**
     * Stores a value into the cache.
     *
     * The $value argument can be any item that can be serialized by PHP, although
     * the method of serialization is left up to the  Implementing Library.
     *
     * The $ttl can be defined in a number of ways. As an integer or
     * DateInterval object the argument defines how long before the cache should
     * expire. As a DateTime object the argument defines the actual expiration
     * time of the object. Implementations are allowed to use a lower time than
     * passed, but should not use a longer one.
     *
     * If no $ttl is passed then the item can be stored indefinitely or a
     * default value can be set by the Implementing Library.
     *
     * Returns true if the item was successfully stored.
     *
     * @param mixed $value
     * @param int|DateInterval|DateTime $ttl
     * @return bool
     */
    function set($value, $ttl = null);

    /**
     * Validates the current state of the item in the cache.
     *
     * An item is considered a miss when it does not exist or has passed its
     * expiration.Implementing Library can define additional miss conditions.
     *
     * @return bool
     */
    function isMiss();

    /**
     * Removes the current key from the cache.
     *
     * Returns true if the item is no longer present (either because it was
     * removed or was not present to begin with).
     *
     * @return bool
     */
    function remove();
}
