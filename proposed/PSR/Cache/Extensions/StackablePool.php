<?php

namespace PSR\Cache\Extensions;

/**
 * Cache\Extensions\StackablePool extends Cache\Pool to provide stacking support.
 *
 * The Cache\Extensions\StackablePool interface adds support for returning
 * Cache\Extensions\StackableItem objects. This works primarily by defining the
 * Key to use a slash as a delimiter, similar to a filesystem, to nest keys.
 * When a StackableItem is cleared it also clears the items nested beneath it.
 */
interface StackablePool extends \PSR\Cache\Pool
{

}