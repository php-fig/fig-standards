namespace PSR\Cache\Extensions;

/**
 * Cache\Extensions\StackableItem extends Cache\Item to provide stacking support.
 *
 * The Cache\Extensions\StackableItem interface adds support for stacking to the
 * base Cache\Item interface. When a StackableItem is cleared it also clears the
 * items nested beneath it.
 */
interface StackableItem extends \PSR\Cache\Item
{

}
