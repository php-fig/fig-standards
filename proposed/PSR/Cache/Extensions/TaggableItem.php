<?php

namespace PSR\Cache\Extensions;

/**
 * Cache\Extensions\TaggableItem extends Cache\Item to provide tagging support.
 *
 * The Cache\Extensions\TaggableItem interface adds support for tagging to the
 * base Cache\Item interface. Items can be added to multiple categories (called
 * tags) that can be used for group invalidation.
 */
interface TaggableItem extends \PSR\Cache\Item
{
    /**
     * Sets the tags for the current item.
     *
     * Accepts an array of strings for the item to be tagged with. The tags
     * passed should overwrite any existing tags, and passing an empty array
     * will cause all tags to be removed. Changes to an Item's tags are not
     * guaranteed to persist unless the "set" function is called.
     *
     * @param array $tags
     * @return void
     */
    function setTags(array $tags = array());
}
