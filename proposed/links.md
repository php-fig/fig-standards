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
- [Microformats Relations List](http://microformats.org/wiki/existing-rel-values#HTML5_link_type_extensions)
- [IANA Link Relations Registry](http://www.iana.org/assignments/link-relations/link-relations.xhtml)


## 1. Specification

### 1.1 

A Hypermedia Link consists of, at minimum:
- A URI representing the target resource being referenced.
- A relationship defining how the target resource relates to the source.

Various other attributes of the Link may exist, depending on the format used. As additional attributes 
are not well-standardized or universal, this specification does not seek to standardize them.


## 2. Package

The interfaces and classes described are provided as part of the
[psr/link](https://packagist.org/packages/psr/link) package.

## 3. Interfaces

### 3.1 `Psr\Http\Link\LinkInterface`

```php
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
     * The target must be a URI or a Relative URI reference.
     *
     * @return string
     */
    public function getHref();

    /**
     * Returns the relationship type(s) of the link.
     *
     * This method returns 0 or more relationship types for a link, expressed
     * as an array of strings.
     *
     * The returned values should be either a simple keyword or an absolute
     * URI. In case a simple keyword is used, it should match one from the
     * IANA registry at:
     *
     * http://www.iana.org/assignments/link-relations/link-relations.xhtml
     *
     * Optionally the microformats.org registry may be used, but this may not
     * be valid in every context:
     *
     * http://microformats.org/wiki/existing-rel-values
     *
     * Private relationship types should always be an absolute URI.
     *
     * @return string[]
     */
    public function getRel();

    /**
     * Returns a list of attributes that describe the target URI.
     *
     * The list should be specified as a key-value list.
     *
     * There is no formal registry of the values that are allowed here, and
     * validity of values is dependant on context.
     *
     * Common values are 'hreflang', 'title', and 'type'. Implementors
     * embedding a serialized version of a link are responsible for only
     * encoding the values they support.
     *
     * Any value that appears that is not valid in the context in which it is
     * used should be ignored.
     *
     * Some attributes, (commonly hreflang) may appear more than once in their
     * context. Attributes such as those may be specified as an array of
     * strings.
     *
     * @return string[]
     */
    public function getAttributes();
}
```

#### 3.2.1 `Psr\Http\Link\LinkableInterface`

```php
<?php
namespace Psr\Http\Link;

/**
 * 
 */
interface LinkableInterface
{
    /**
     * Returns a collection of LinkInterface objects.
     *
     * The collection may be an array or any PHP \Traversable object.
     *
     * @return LinkInterface[]|\Traversable
     */
    public function getLinks();

    /**
     * Returns a collection of LinkInterface objects that have a specific relationship.
     *
     * The collection may be an array or any PHP \Traversable object.
     *
     * @return LinkInterface[]|\Traversable
     */
    public function getLinksByRel($rel);
}
```
