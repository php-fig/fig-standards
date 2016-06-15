# Link definition interfaces

Hypermedia links are becoming an increasingly important part of the web, in both HTML contexts
and various API format contexts. However, there is no single common hypermedia format, nor
is there a common way to represent Links between formats.

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

*    **Implementing Object** -An object that implements one of the interfaces defined by this
specification.

*    **Serializer** - A library or other system that takes one or more Link objects and produces
a serialized representation of it in some defined format.


### 1.2 Attributes

All links MAY include zero or more additional attributes beyond the URI and relationship.
There is no formal registry of the values that are allowed here, and validity of values
is dependent on context and often on a particular serialization format.  Commonly supported
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
have no value when the attribute's presence has a boolean meaning.  This rule applies
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
application or use case.  Such relationships MUST use an absolute URI.

## 1.4 Link Templates

[RFC 6570](https://tools.ietf.org/html/rfc6570) defines a format for URI templates, that is,
a pattern for a URI that is expected to be filled in with values provided by a client
tool.  Some hypermedia formats support templated links while others do not, and may 
have a special way to denote that a link is a template.  A Serializer for a format 
that does not support URI Templates MUST ignore any templated Links it encounters.

## 2. Package

The interfaces and classes described are provided as part of the
[psr/link](https://packagist.org/packages/psr/link) package.

## 3. Interfaces

### 3.1 `Psr\Http\Link\LinkInterface`

~~~php
<?php
namespace Psr\Http\Link;

/**
 *
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
    public function getRel();

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

#### 3.2.1 `Psr\Http\Link\LinkCollectionInterface`

~~~php
<?php
namespace Psr\Http\Link;

/**
 *
 */
interface LinkCollectionInterface
{
    /**
     * Returns a collection of LinkInterface objects.
     *
     * The collection may be an array or any PHP \Traversable object. If no links
     * are available, an empty array or \Traversable MUST be returned.
     *
     * @return LinkInterface[]|\Traversable
     */
    public function getLinks();

    /**
     * Returns a collection of LinkInterface objects that have a specific relationship.
     *
     * The collection may be an array or any PHP \Traversable object. If no links
     * with that relationship are available, an empty array or \Traversable MUST be returned.
     *
     * @return LinkInterface[]|\Traversable
     */
    public function getLinksByRel($rel);
}
~~~
