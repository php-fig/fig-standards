# PSR-Cache Meta Document

## 1. Summary

Caching is a common way to improve the performance of any project, making
caching libraries one of the most common features of many frameworks and
libraries. This has lead to a situation where many libraries roll their own
caching libraries, with various levels of functionality. These differences are
causing developers to have to learn multiple systems which may or may not
provide the functionality they need. In addition, the developers of caching
libraries themselves face a choice between only supporting a limited number
of frameworks or creating a large number of adapter classes.

## 2. Why Bother?

A common interface for caching systems will solve these problems. Library and
framework developers can count on the caching systems working the way they're
expecting, while the developers of caching systems will only have to implement
a single set of interfaces rather than a whole assortment of adapters.

Moreover, the implementation presented here is designed for future extensibility.
It allows a variety of internally-different but API-compatible implementations
and offers a clear path for future extension by later PSRs or by specific
implementers.

Pros:
* A standard interface for caching allows free-standing libraries to support
caching of intermediary data without effort; they may simply (optionally) depend
on this standard interface and leverage it without being concerned about
implementation details.
* Commonly developed caching libraries shared by multiple projects, even if
they extend this interface, are likely to be more robust than a dozen separately
developed implementations.

Cons:
* Any interface standardization runs the risk of stifling future innovation as
being "not the Way It's Done(tm)".  However, we believe caching is a sufficiently
commoditized problem space that the extension capability offered here mitigates
any potential risk of stagnation.

## 3. Scope

### 3.1 Goals

* A common interface for basic and intermediate-level caching needs.
* A clear mechanism for extending the specification to support advanced features,
both by future PSRs or by individual implementations. This mechanism must allow
for multiple independent extensions without collision.

### 3.2 Non-Goals

* Architectural compatibility with all existing cache implementations.
* Advanced caching features such as namespacing or tagging that are used by a
minority of users.

## 4. Approaches

### 4.1 Chosen Approach

This specification adopts a "repository model" or "data mapper" model for caching
rather than the more traditional "expire-able key-value" model.  The primary
reason is flexibility.  A simple key/value model is much more difficult to extend.

The model here mandates the use of a CacheItem object, which represents a cache
entry, and a Pool object, which is a given store of cached data.  Items are
retrieved from the pool, interacted with, and returned to it.  While a bit more
verbose at times it offers a good, robust, flexible approach to caching,
especially in cases where caching is more involved than simply saving and
retrieving a string.

Most method names were chosen based on common practice and method names in a
survey of member projects and other popular non-member systems.

Pros:

* Flexible and extensible
* Allows a great deal of variation in implementation without violating the interface
* Does not implicitly expose object constructors as a pseudo-interface.

Cons:

* A bit more verbose than the naive approach

Examples:

Some common usage patterns are shown below.  These are non-normative but should
demonstrate the application of some design decisions.

~~~php
/**
 * Gets a list of available widgets.
 *
 * In this case, we assume the widget list changes so rarely that we want
 * the list cached forever until an explicit clear.
 */
function get_widget_list()
{
    $pool = get_cache_pool('widgets');
    $item = $pool->getItem('widget_list');
    if (!$item->isHit()) {
        $value = compute_expensive_widget_list();
        $item->set($value);
        $pool->save($item);
    }
    return $item->get();
}
~~~

~~~php
/**
 * Caches a list of available widgets.
 *
 * In this case, we assume a list of widgets has been computed and we want
 * to cache it, regardless of what may already be cached.
 */
function save_widget_list($list)
{
    $pool = get_cache_pool('widgets');
    $item = $pool->getItem('widget_list');
    $item->set($list);
    $pool->save($item);
}
~~~

~~~php
/**
 * Clears the list of available widgets.
 *
 * In this case, we simply want to remove the widget list from the cache. We
 * don't care if it was set or not; the post condition is simply "no longer set".
 */
function clear_widget_list()
{
    $pool = get_cache_pool('widgets');
    $pool->deleteItems(['widget_list']);
}
~~~

~~~php
/**
 * Clears all widget information.
 *
 * In this case, we want to empty the entire widget pool. There may be other
 * pools in the application that will be unaffected.
 */
function clear_widget_cache()
{
    $pool = get_cache_pool('widgets');
    $pool->clear();
}
~~~

~~~php
/**
 * Load widgets.
 *
 * We want to get back a list of widgets, of which some are cached and some
 * are not. This of course assumes that loading from the cache is faster than
 * whatever the non-cached loading mechanism is.
 *
 * In this case, we assume widgets may change frequently so we only allow them
 * to be cached for an hour (3600 seconds). We also cache newly-loaded objects
 * back to the pool en masse.
 *
 * Note that a real implementation would probably also want a multi-load
 * operation for widgets, but that's irrelevant for this demonstration.
 */
function load_widgets(array $ids)
{
    $pool = get_cache_pool('widgets');
    $keys = array_map(function($id) { return 'widget.' . $id; }, $ids);
    $items = $pool->getItems($keys);

    $widgets = array();
    foreach ($items as $key => $item) {
        if ($item->isHit()) {
            $value = $item->get();
        } else {
            $value = expensive_widget_load($id);
            $item->set($value);
            $item->expiresAfter(3600);
            $pool->saveDeferred($item, true);
        }
        $widget[$value->id()] = $value;
    }
    $pool->commit(); // If no items were deferred this is a no-op.

    return $widgets;
}
~~~

~~~php
/**
 * This examples reflects functionality that is NOT included in this
 * specification, but is shown as an example of how such functionality MIGHT
 * be added by extending implementations.
 */

interface TaggablePoolInterface extends Psr\Cache\CachePoolInterface
{
    /**
     * Clears only those items from the pool that have the specified tag.
     */
    clearByTag($tag);
}

interface TaggableItemInterface extends Psr\Cache\CacheItemInterface
{
    public function setTags(array $tags);
}

/**
 * Caches a widget with tags.
 */
function set_widget(TaggablePoolInterface $pool, Widget $widget)
{
    $key = 'widget.' . $widget->id();
    $item = $pool->getItem($key);

    $item->setTags($widget->tags());
    $item->set($widget);
    $pool->save($item);
}
~~~

### 4.2 Alternative: "Weak item" approach

A variety of earlier drafts took a simpler "key value with expiration" approach,
also known as a "weak item" approach.  In this model, the "Cache Item" object
was really just a dumb array-with-methods object.  Users would instantiate it
directly, then pass it to a cache pool.  While more familiar, that approach
effectively prevented any meaningful extension of the Cache Item.  It effectively
made the Cache Item's constructor part of the implicit interface, and thus
severely curtailed extensibility or the ability to have the cache item be where
the intelligence lives.

In a poll conducted in June 2013, most participants showed a clear preference for
the more robust if less conventional "Strong item" / repository approach, which
was adopted as the way forward.

Pros:
* More traditional approach.

Cons:
* Less extensible or flexible.

### 4.3 Alternative: "Naked value" approach

Some of the earliest discussions of the Cache spec suggested skipping the Cache
Item concept all together and just reading/writing raw values to be cached.
While simpler, it was pointed out that made it impossible to tell the difference
between a cache miss and whatever raw value was selected to represent a cache
miss.  That is, if a cache lookup returned NULL it's impossible to tell if there
was no cached value or if NULL was the value that had been cached.  (NULL is a
legitimate value to cache in many cases.)

Most more robust caching implementations we reviewed -- in particular the Stash
caching library and the home-grown cache system used by Drupal -- use some sort
of structured object on `get` at least to avoid confusion between a miss and a
sentinel value.  Based on that prior experience FIG decided that a naked value
on `get` was impossible.

### 4.4 Alternative: ArrayAccess Pool

There was a suggestion to make a Pool implement ArrayAccess, which would allow
for cache get/set operations to use array syntax.  That was rejected due to
limited interest, limited flexibility of that approach (trivial get and set with
default control information is all that's possible), and because it's trivial
for a particular implementation to include as an add-on should it desire to
do so.

## 5. People

### 5.1 Editor

* Larry Garfield

### 5.2 Sponsors

* Paul Dragoonis, PPI Framework (Coordinator)
* Robert Hafner, Stash

## 6. Votes

[Acceptance vote on the mailing list](https://groups.google.com/forum/#!msg/php-fig/dSw5IhpKJ1g/O9wpqizWAwAJ)

## 7. Relevant Links

_**Note:** Order descending chronologically._

* [Survey of existing cache implementations][1], by @dragoonis
* [Strong vs. Weak informal poll][2], by @Crell
* [Implementation details informal poll][3], by @Crell

[1]: https://docs.google.com/spreadsheet/ccc?key=0Ak2JdGialLildEM2UjlOdnA4ekg3R1Bfeng5eGlZc1E#gid=0
[2]: https://docs.google.com/spreadsheet/ccc?key=0AsMrMKNHL1uGdDdVd2llN1kxczZQejZaa3JHcXA3b0E#gid=0
[3]: https://docs.google.com/spreadsheet/ccc?key=0AsMrMKNHL1uGdEE3SU8zclNtdTNobWxpZnFyR0llSXc#gid=1

## 8. Errata

### 8.1 Handling of incorrect DateTime values in expiresAt()

The `CacheItemInterface::expiresAt()` method's `$expiration` parameter is untyped
in the interface, but in the docblock is specified as `\DateTimeInterface`.  The
intent is that either a `\DateTime` or `\DateTimeImmutable` object is allowed.
However, `\DateTimeInterface` and `\DateTimeImmutable` were added in PHP 5.5, and
the authors chose not to impose a hard syntactic requirement for PHP 5.5 on the
specification.

Despite that, implementers MUST accept only `\DateTimeInterface` or compatible types
(such as `\DateTime` and `\DateTimeImmutable`) as if the method was explicitly typed.
(Note that the variance rules for a typed parameter may vary between language versions.)

Simulating a failed type check unfortunately varies between PHP versions and thus is not
recommended.  Instead, implementors SHOULD throw an instance of `\Psr\Cache\InvalidArgumentException`.  
The following sample code is recommended in order to enforce the type check on the expiresAt()
method:

```php

class ExpiresAtInvalidParameterException implements Psr\Cache\InvalidArgumentException {}

// ...

if (! (
        null === $expiration
        || $expiration instanceof \DateTime
        || $expiration instanceof \DateTimeInterface
)) {
    throw new ExpiresAtInvalidParameterException(sprintf(
        'Argument 1 passed to %s::expiresAt() must be an instance of DateTime or DateTimeImmutable; %s given',
        get_class($this),
        is_object($expiration) ? get_class($expiration) : gettype($expiration)
    ));
}
```
