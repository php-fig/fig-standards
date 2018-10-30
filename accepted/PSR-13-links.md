# Link definition interfaces

Hypermedia links are becoming an increasingly important part of the web, in both HTML contexts
and various API format contexts. However, there is no single common hypermedia format, nor
is there a common way to represent links between formats.

This specification aims to provide PHP developers with a simple, common way of representing a
hypermedia link independently of the serialization format that is used. That in turn allows
a system to serialize a response with hypermedia links into one or more wire formats independently
of the process of deciding what those links should be.

The key words "MUST", "MUST NOT", "REQUIRED", "SHALL", "SHALL NOT", "SHOULD",
"SHOULD NOT", "RECOMMENDED", "MAY", and "OPTIONAL" in this document are to be
interpreted as described in [RFC 2119](http://tools.ietf.org/html/rfc2119).

### References

- [RFC 2119](http://tools.ietf.org/html/rfc2119)
- [RFC 4287](https://tools.ietf.org/html/rfc4287)
- [RFC 5988](https://tools.ietf.org/html/rfc5988)
- [RFC 6570](https://tools.ietf.org/html/rfc6570)
- [IANA Link Relations Registry](http://www.iana.org/assignments/link-relations/link-relations.xhtml)
- [Microformats Relations List](http://microformats.org/wiki/existing-rel-values#HTML5_link_type_extensions)

## 1. Specification

### 1.1 Basic links

A Hypermedia Link consists of, at minimum:
- A URI representing the target resource being referenced.
- A relationship defining how the target resource relates to the source.

Various other attributes of the Link may exist, depending on the format used. As additional attributes
are not well-standardized or universal, this specification does not seek to standardize them.

For the purposes of this specification, the following definitions apply.

*    **Implementing Object** - An object that implements one of the interfaces defined by this
specification.

*    **Serializer** - A library or other system that takes one or more Link objects and produces
a serialized representation of it in some defined format.

### 1.2 Attributes

All links MAY include zero or more additional attributes beyond the URI and relationship.
There is no formal registry of the values that are allowed here, and validity of values
is dependent on context and often on a particular serialization format. Commonly supported
values include 'hreflang', 'title', and 'type'.

Serializers MAY omit attributes on a link object if required to do so by the serialization
format. However, serializers SHOULD encode all provided attributes possible in order to
allow for user-extension unless prevented by a serialization format's definition.

Some attributes (commonly `hreflang`) may appear more than once in their context. Therefore,
an attribute value MAY be an array of values rather than a simple value. Serializers MAY
encode that array in whatever format is appropriate for the serialized format (such
as a space-separated list, comma-separated list, etc.). If a given attribute is not
allowed to have multiple values in a particular context, serializers MUST use the first
value provided and ignore all subsequent values.

If an attribute value is boolean `true`, serializers MAY use abbreviated forms if appropriate
and supported by a serialization format. For example, HTML permits attributes to
have no value when the attribute's presence has a boolean meaning. This rule applies
if and only if the attribute is boolean `true`, not for any other "truthy" value
in PHP such as integer 1.

If an attribute value is boolean `false`, serializers SHOULD omit the attribute entirely
unless doing so changes the semantic meaning of the result. This rule applies if
and only if the attribute is boolean `false`, not for any other "falsey" value in PHP
such as integer 0.

### 1.3 Relationships

Link relationships are defined as strings, and are either a simple keyword in
case of a publicly defined relationship or an absolute URI in the case of a
private relationships.

In case a simple keyword is used, it SHOULD match one from the IANA registry at:

http://www.iana.org/assignments/link-relations/link-relations.xhtml

Optionally the microformats.org registry MAY be used, but this may not be valid
in every context:

http://microformats.org/wiki/existing-rel-values

A relationship that is not defined in one of the above registries or a similar
public registry is considered "private", that is, specific to a particular
application or use case. Such relationships MUST use an absolute URI.

## 1.4 Link Templates

[RFC 6570](https://tools.ietf.org/html/rfc6570) defines a format for URI templates, that is,
a pattern for a URI that is expected to be filled in with values provided by a client
tool. Some hypermedia formats support templated links while others do not, and may
have a special way to denote that a link is a template. A Serializer for a format
that does not support URI Templates MUST ignore any templated Links it encounters.

## 1.5 Evolvable providers

In some cases, a Link Provider may need the ability to have additional links
added to it. In others, a link provider is necessarily read-only, with links
derived at runtime from some other data source. For that reason, modifiable providers
are a secondary interface that may optionally be implemented.

Additionally, some Link Provider objects, such as PSR-7 Response objects, are
by design immutable. That means methods to add links to them in-place would be
incompatible. Therefore, the `EvolvableLinkProviderInterface`'s single method
requires that a new object be returned, identical to the original but with
an additional Link object included.

## 1.6 Evolvable link objects

Link objects are in most cases value objects. As such, allowing them to evolve
in the same fashion as PSR-7 value objects is a useful option. For that reason,
an additional EvolvableLinkInterface is included that provides methods to
produce new object instances with a single change. The same model is used by PSR-7
and, thanks to PHP's copy-on-write behavior, is still CPU and memory efficient.

There is no evolvable method for templated values, however, as the templated value of a
link is based exclusively on the href value. It MUST NOT be set independently, but
derived from whether or not the href value is an RFC 6570 link template.

## 2. Package

The interfaces and classes described are provided as part of the
[psr/link](https://packagist.org/packages/psr/link) package.

## 3. Interfaces

### 3.1 `Psr\Link\LinkInterface`

~~~php
<?php

namespace Psr\Link;

/**
 * A readable link object.
 */
interface LinkInterface
{
    /**
     * Returns the target of the link.
     *
     * The target link must be one of:
     * - An absolute URI, as defined by RFC 5988.
     * - A relative URI, as defined by RFC 5988. The base of the relative link
     *     is assumed to be known based on context by the client.
     * - A URI template as defined by RFC 6570.
     *
     * If a URI template is returned, isTemplated() MUST return True.
     *
     * @return string
     */
    public function getHref();

    /**
     * Returns whether or not this is a templated link.
     *
     * @return bool
     *   True if this link object is templated, False otherwise.
     */
    public function isTemplated();

    /**
     * Returns the relationship type(s) of the link.
     *
     * This method returns 0 or more relationship types for a link, expressed
     * as an array of strings.
     *
     * @return string[]
     */
    public function getRels();

    /**
     * Returns a list of attributes that describe the target URI.
     *
     * @return array
     *   A key-value list of attributes, where the key is a string and the value
     *  is either a PHP primitive or an array of PHP strings. If no values are
     *  found an empty array MUST be returned.
     */
    public function getAttributes();
}
~~~

### 3.2 `Psr\Link\EvolvableLinkInterface`

~~~php
<?php

namespace Psr\Link;

/**
 * An evolvable link value object.
 */
interface EvolvableLinkInterface extends LinkInterface
{
    /**
     * Returns an instance with the specified href.
     *
     * @param string $href
     *   The href value to include. It must be one of:
     *     - An absolute URI, as defined by RFC 5988.
     *     - A relative URI, as defined by RFC 5988. The base of the relative link
     *       is assumed to be known based on context by the client.
     *     - A URI template as defined by RFC 6570.
     *     - An object implementing __toString() that produces one of the above
     *       values.
     *
     * An implementing library SHOULD evaluate a passed object to a string
     * immediately rather than waiting for it to be returned later.
     *
     * @return static
     */
    public function withHref($href);

    /**
     * Returns an instance with the specified relationship included.
     *
     * If the specified rel is already present, this method MUST return
     * normally without errors, but without adding the rel a second time.
     *
     * @param string $rel
     *   The relationship value to add.
     * @return static
     */
    public function withRel($rel);

    /**
     * Returns an instance with the specified relationship excluded.
     *
     * If the specified rel is already not present, this method MUST return
     * normally without errors.
     *
     * @param string $rel
     *   The relationship value to exclude.
     * @return static
     */
    public function withoutRel($rel);

    /**
     * Returns an instance with the specified attribute added.
     *
     * If the specified attribute is already present, it will be overwritten
     * with the new value.
     *
     * @param string $attribute
     *   The attribute to include.
     * @param string $value
     *   The value of the attribute to set.
     * @return static
     */
    public function withAttribute($attribute, $value);

    /**
     * Returns an instance with the specified attribute excluded.
     *
     * If the specified attribute is not present, this method MUST return
     * normally without errors.
     *
     * @param string $attribute
     *   The attribute to remove.
     * @return static
     */
    public function withoutAttribute($attribute);
}
~~~

#### 3.2 `Psr\Link\LinkProviderInterface`

~~~php
<?php

namespace Psr\Link;

/**
 * A link provider object.
 */
interface LinkProviderInterface
{
    /**
     * Returns an iterable of LinkInterface objects.
     *
     * The iterable may be an array or any PHP \Traversable object. If no links
     * are available, an empty array or \Traversable MUST be returned.
     *
     * @return LinkInterface[]|\Traversable
     */
    public function getLinks();

    /**
     * Returns an iterable of LinkInterface objects that have a specific relationship.
     *
     * The iterable may be an array or any PHP \Traversable object. If no links
     * with that relationship are available, an empty array or \Traversable MUST be returned.
     *
     * @return LinkInterface[]|\Traversable
     */
    public function getLinksByRel($rel);
}
~~~

#### 3.3 `Psr\Link\EvolvableLinkProviderInterface`

~~~php
<?php

namespace Psr\Link;

/**
 * An evolvable link provider value object.
 */
interface EvolvableLinkProviderInterface extends LinkProviderInterface
{
    /**
     * Returns an instance with the specified link included.
     *
     * If the specified link is already present, this method MUST return normally
     * without errors. The link is present if $link is === identical to a link
     * object already in the collection.
     *
     * @param LinkInterface $link
     *   A link object that should be included in this collection.
     * @return static
     */
    public function withLink(LinkInterface $link);

    /**
     * Returns an instance with the specifed link removed.
     *
     * If the specified link is not present, this method MUST return normally
     * without errors. The link is present if $link is === identical to a link
     * object already in the collection.
     *
     * @param LinkInterface $link
     *   The link to remove.
     * @return static
     */
    public function withoutLink(LinkInterface $link);
}
~~~
