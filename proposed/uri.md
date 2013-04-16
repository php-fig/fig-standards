Uniform resource identifier (URI) interfaces
============================================

This document describes common interfaces for representing uniform resource
identifiers (URIs).

The key words "MUST", "MUST NOT", "REQUIRED", "SHALL", "SHALL NOT", "SHOULD",
"SHOULD NOT", "RECOMMENDED", "MAY", and "OPTIONAL" in this document are to be
interpreted as described in [RFC 2119][].

[RFC 2119]: http://tools.ietf.org/html/rfc2119

1. Specification
----------------

### 1.1 Basics

A URI identifies an abstract or physical resource, and is defined in [RFC 3986][].

Three interfaces are defined:

1. `Psr\Uri\UriInterface` is a base interface that MUST NOT be implemented
   directly.
2. `Psr\Uri\OpaqueUriInterface` represents opaque URIs, which are absolute and
   whose hierarchical part does not begin with a slash character ("/").
3. `Psr\Uri\HierarchicalUriInterface` represents hiearchical URIs, which are
   either absolute and whose scheme-specific part begins with a slash ("/"), or
   relative, that is, a URI that does not specify a scheme.

Implementors are not expected to provide implementations for both
`OpaqueUriInterface` and `HierarchicalUriInterface`, but instead provide the
form that is required.

For example, a HTTP client might provide a `Http` object (which implements
`HierarchicalUriInterface` and allows URIs with the scheme of "http" or
"https"), whereas a catalog-style library might provide a `Urn` object (which
implements `OpaqueUriInterface` and uses the scheme "urn").

[RFC 3986]: http://tools.ietf.org/html/rfc3986

### 1.2 Modifying URI objects

The interfaces do not define setters to modify URIs. Implementations MAY choice
to add these, or instead use constructors/builder objects to create new URIs.

The `HierarchicalUriInterface` does contains methods to normalize the URI, and
to resolve/relativize against another URI. These methods return new URI objects.

2. Package
----------

The interfaces described as well as a test suite to verify your implementation
are provided as part of the [psr/uri](https://packagist.org/packages/psr/uri)
package.

3. Interfaces
-------------

### 3.1 `Psr\Uri\UriInterface`

```php
<?php

namespace Psr\Uri;

use Psr\Uri\Exception\UnexpectedValueException;

/**
 * Represents a uniform resource identifier (URI), which identifies an abstract
 * or physical resource.
 *
 * URIs are either opaque or hierarchical, and are represented by
 * `OpaqueUriInterface` and `HierarchicalUriInterface` respectively.
 *
 * Absolute URIs are either opaque or hierarchical. Relative URIs are always
 * hierarchical.
 *
 * Some examples of URIs are:
 *
 * - <samp>http://php.net/manual/en/</samp>
 * - <samp>../../manual/</samp>
 * - <samp>mailto:example@example.com</samp>
 * - <samp>news:comp.lang.php</samp>
 *
 * URIs in string form have the syntax:
 *
 * <pre>
 * [scheme:]hierarchical-part[?query][#fragment]
 * </pre>
 *
 * where square brackets "[...]" delineate optional components and the
 * characters ":", "?" and "#" stand for themselves.
 *
 * For example:
 *
 * <pre>
 * Hierarchical:   foo://example.com:8080/bar?name=ferret#teeth
 *                 \_/   \__________________/ \_________/ \___/
 *                  |             |                |        |
 *               scheme   hierarchical part      query   fragment
 *                  |   __________|_________   ____|____   _|_
 *                 / \ /                    \ /         \ /   \
 *       Opaque:   urn:example:example:animal?name=ferret#teeth
 * </pre>
 *
 * This interface MUST NOT be implemented directly, instead use
 * `OpaqueUriInterface` or `HierarchicalUriInterface` as appropriate.
 *
 * This interface MAY be type hinted, but only where any URI form is
 * acceptable.
 *
 * @link http://tools.ietf.org/html/rfc3986 RFC 3986
 */
interface UriInterface
{
    /**
     * Returns the URI as an encoded string.
     *
     * This MUST produce the same result as {@link toEncodedString()}.
     *
     * @return string URI as an encoded string.
     */
    public function __toString();

    /**
     * Returns the URI as a decoded string.
     *
     * For example, the encoded URI:
     *
     * <samp>http://user:pa55w%3Frd@host:80/doc%7Csearch?q=green%20robots#over%206%22</samp>
     *
     * would be returned as:
     *
     * <samp>http://user:pa55w?rd@host:80/doc|search?q=green robots#over 6"</samp>
     *
     * @return string URI as a decoded string.
     *
     * @see toEncodedString()
     */
    public function toDecodedString();

    /**
     * Returns the URI as an encoded string.
     *
     * For example, the decoded URI:
     *
     * <samp>http://user:pa55w?rd@host:80/doc|search?q=green robots#over 6"</samp>
     *
     * would be returned as:
     *
     * <samp>http://user:pa55w%3Frd@host:80/doc%7Csearch?q=green%20robots#over%206%22</samp>
     *
     * @return string URI as an encoded string.
     *
     * @see toDecodedString()
     *
     * @link http://tools.ietf.org/html/rfc3986#section-2.1 RFC 3986 § 2.1
     */
    public function toEncodedString();

    /**
     * Gets the decoded scheme component.
     *
     * The scheme component consists of a sequence of characters beginning with
     * a letter and followed by any combination of letters, digits, plus ("+"),
     * period ("."), or hyphen ("-").
     *
     * The scheme component MUST be treated as case-insensitive.
     * Implementations SHOULD return a lowercase scheme.
     *
     * Implementations MUST NOT return the succeeding colon (":").
     *
     * @return string|null Scheme component, or `null` if not set.
     *
     * @link http://tools.ietf.org/html/rfc3986#section-3.1 RFC 3986 § 3.1
     */
    public function getScheme();

    /**
     * Gets the decoded hierarchical part.
     *
     * Implementations MUST return an empty string (as opposed to `null`) when
     * no hierarchical part is set.
     *
     * Implementations MUST NOT return the preceding colon (":"). They MUST NOT
     * return a succeeding question mark ("?") or number sign ("#") that
     * delimits it from a query or fragment component respectively.
     *
     * @return string Hierarchical part.
     */
    public function getHierarchicalPart();

    /**
     * Gets the decoded query component.
     *
     * Implementations MUST NOT return the preceding question mark ("?") nor a
     * succeeding number sign ("#").
     *
     * @return string|null Query component, or `null` if not set.
     *
     * @link http://tools.ietf.org/html/rfc3986#section-3.4 RFC 3986 § 3.4
     */
    public function getQuery();

    /**
     * Gets the decoded query component containing "key=value" pairs as an
     * associative array.
     *
     * Implementations MUST treat keys ending with one or more sets of square
     * brackets ("[...]") optionally containing a value as nested array keys.
     *
     * For example, the query string:
     *
     * <pre>
     * key1=value1&key2[key3][]=value2&key2[key3][]=value3
     * </pre>
     *
     * would be returned as:
     *
     * <pre>
     * array(
     *     'key1' => 'value1',
     *     'key2' => array(
     *          'key3' => array(
     *              0 => 'value2',
     *              1 => 'value3'
     *          )
     *     )
     * )
     * </pre>
     *
     * Implementations MUST recognise ampersands ("&") as delimiting symbols,
     * and MAY recognise other characters such as semicolons (";").
     *
     * If the query string is empty, or has not been set, an empty array MUST
     * be returned. Otherwise if the query string is not purely comprised of
     * "key=value" pairs, an `UnexpectedValueException` MUST be thrown.
     *
     * @return array Query component.
     *
     * @throws UnexpectedValueException If the query string is not "key=value" pairs.
     */
    public function getQueryAsArray();

    /**
     * Gets the decoded fragment component.
     *
     * Implementations MUST NOT return the preceding number sign ("#").
     *
     * @return string|null Fragment component, or `null` if not set.
     *
     * @link http://tools.ietf.org/html/rfc3986#section-3.5 RFC 3986 § 3.5
     */
    public function getFragment();
}
```

### 3.2 `Psr\Uri\OpaqueUriInterface`

```php
<?php

namespace Psr\Uri;

/**
 * Represents an opaque uniform resource identifier (URI).
 *
 * This is an absolute URI whose hierarchical part does not begin with a
 * slash character ("/").
 *
 * Some examples of opaque URIs are:
 *
 * - <samp>mailto:example@example.com</samp>
 * - <samp>news:comp.lang.php</samp>
 * - <samp>urn:isbn:096139210x</samp>
 *
 * Opaque URIs in string form have the syntax:
 *
 * <pre>
 * scheme:hierarchical-part[?query][#fragment]
 * </pre>
 *
 * where square brackets "[...]" delineate optional components and the
 * characters ":", "?", and "#" stand for themselves.
 *
 * For example:
 *
 * <pre>
 *   urn:example:animal?name=ferret#teeth
 *   \_/ \____________/ \_________/ \___/
 *    |         |            |        |
 * scheme  hierarchical    query   fragment
 *             part
 * </pre>
 */
interface OpaqueUriInterface extends UriInterface
{
}
```

### 3.3 `Psr\Uri\HierarchicalUriInterface`

```php
<?php

namespace Psr\Uri;

/**
 * Represents a hierarchical uniform resource identifier (URI).
 *
 * This is either an absolute URI whose scheme-specific part begins with a
 * slash ("/"), or a relative URI, that is, a URI that does not specify a
 * scheme.
 *
 * Some examples of hierarchical URIs are:
 *
 * - <samp>http://php.net/manual/en/</samp>
 * - <samp>manual/en/language.oop5.interfaces.php</samp>
 * - <samp>../../manual/</samp>
 * - <samp>file:///~/calendar</samp>
 *
 * Hierarchical URIs in string form have the syntax:
 *
 * <pre>
 * [scheme:][//authority][path][?query][#fragment]
 * </pre>
 *
 * where square brackets "[...]" delineate optional components and the
 * characters ":", "/", "?", and "#" stand for themselves.
 *
 * The authority component has the syntax:
 *
 * <pre>
 * [user-info@]host[:port]
 * </pre>
 *
 * The path component of a hierarchical URI is itself said to be absolute if it
 * begins with a slash character ("/"); otherwise it is relative. The path of a
 * hierarchical URI that is either absolute or specifies an authority is always
 * absolute.
 *
 * For example:
 *
 * <pre>
 *   foo://user:password@example.com:8042/over/there?name=ferret#teeth
 *   \_/   \___________/ \_________/ \__/\_________/ \_________/ \___/
 *    |          |            |       |       |           |        |
 *    |      user info      host     port     |           |        |
 *    |    \____________________________/     |           |        |
 *    |                  |                    |           |        |
 *    |              authority               path         |        |
 *    |  \_________________________________________/      |        |
 *    |                       |                           |        |
 * scheme             hierarchical part                 query   fragment
 * </pre>
 */
interface HierarchicalUriInterface extends UriInterface
{
    /**
     * Whether this URI is absolute or not.
     *
     * A URI is absolute if, and only if, it has a scheme component.
     *
     * @return bool `true` if the URI is absolute, otherwise `false`.
     */
    public function isAbsolute();

    /**
     * Gets the hierarchical part.
     *
     * Implementations MUST return the preceding double slash ("//") if the
     * authority component is present. They MUST NOT return a succeeding
     * question mark ("?") or number sign ("#") that delimits it from a path,
     * query or fragment component respectively.
     *
     * @return string|null Hierarchical part, or `null` if not set.
     */
    public function getHierarchicalPart();

    /**
     * Gets the authority component.
     *
     * If the port subcomponent has been set as the known default for the
     * scheme component it SHOULD NOT be included.
     *
     * Implementations MUST NOT return the preceding double slash ("//") nor a
     * subsequent slash ("/"), question mark ("?") or number sign ("#") that
     * delimits it from a path, query or fragment component respectively.
     *
     * @return string Authority component, or `null` if not set.
     *
     * @link http://tools.ietf.org/html/rfc3986#section-3.2 RFC 3986 § 3.2
     */
    public function getAuthority();

    /**
     * Gets the user info subcomponent of the authority.
     *
     * Implementations MUST NOT return the succeeding at-sign ("@").
     *
     * @return string|null User info component, or `null` if not set.
     *
     * @link http://tools.ietf.org/html/rfc3986#section-3.2.1 RFC 3986 § 3.2.1
     */
    public function getUserInfo();

    /**
     * Gets the host subcomponent of the authority.
     *
     * It MUST be treated as case-insensitive. Implementations SHOULD return
     * lowercase registered names and hexadecimal addresses.
     *
     * @return string|null Host subcomponent, or `null` if not set.
     *
     * @link http://tools.ietf.org/html/rfc3986#section-3.2.2 RFC 3986 § 3.2.2
     */
    public function getHost();

    /**
     * Gets the port subcomponent of the authority.
     *
     * If the port has not been set this MAY return the default port for the
     * scheme if known.
     *
     * @return int|null Port subcomponent, or `null` if not set.
     *
     * @link http://tools.ietf.org/html/rfc3986#section-3.2.3 RFC 3986 § 3.2.3
     */
    public function getPort();

    /**
     * Gets the decoded path component.
     *
     * Implementations MUST return an empty string (as opposed to `null`) when
     * no path is set.
     *
     * @return string Path.
     *
     * @link http://tools.ietf.org/html/rfc3986#section-3.3 RFC 3986 § 3.3
     */
    public function getPath();

    /**
     * Normalizes the path component.
     *
     * A new hierarchical URI is constructed that is identical to this URI,
     * except that its path is computed by normalizing this URI's path.
     *
     * 1. All "." segments are removed.
     * 2. If a ".." segment is preceded by a non-".." segment then both of
     *    these segments are removed. This step is repeated until it is no
     *    longer applicable.
     *
     * A normalized path will begin with one or more ".." segments if there
     * were insufficient non-".." segments preceding them to allow their
     * removal, otherwise a normalized path will not contain any "." or ".."
     * segments.
     *
     * For example, the URIs:
     *
     * - <samp>http://www.php.net/manual/en/./language.oop5.interfaces.php</samp>
     * - <samp>http://www.php.net/manual/en/foo/bar/../../language.oop5.interfaces.php</samp>
     * - <samp>foo/../language.oop5.interfaces.php</samp>
     * - <samp>../language.oop5.interfaces.php</samp>
     * - <samp>./language.oop5.interfaces.php</samp>
     *
     * become:
     *
     * - <samp>http://www.php.net/manual/en/language.oop5.interfaces.php</samp>
     * - <samp>http://www.php.net/manual/en/language.oop5.interfaces.php</samp>
     * - <samp>language.oop5.interfaces.php</samp>
     * - <samp>../language.oop5.interfaces.php</samp>
     * - <samp>language.oop5.interfaces.php</samp>
     *
     * respectively.
     *
     * @return HierarchicalUriInterface A URI equivalent to this URI, but whose path is in normal form.
     *
     * @link http://tools.ietf.org/html/rfc3986#section-6.2.2 RFC 3986 § 6.2.2
     */
    public function normalize();

    /**
     * Resolves the given URI against this URI.
     *
     * If the given URI is already absolute, then the given URI is returned.
     *
     * If the given URI's fragment component is defined, its path component is
     * empty, and its scheme, authority, and query components are undefined,
     * then a URI with the given fragment but with all other components equal
     * to those of this URI is returned. This allows a URI representing a
     * standalone fragment reference, such as "#foo", to be usefully resolved
     * against a base URI.
     *
     * Otherwise a new URI is constructed with this URI's scheme and the given
     * URI's query and fragment components.
     *
     * If the given URI has an authority component then the new URI's authority
     * and path are taken from the given URI.
     *
     * Otherwise the new URI's authority component is copied from this URI, and
     * its path is computed as follows:
     *
     * If the given URI's path is absolute then the new URI's path is taken
     * from the given URI.
     *
     * Otherwise the given URI's path is relative, and so the new URI's path is
     * computed by resolving the path of the given URI against the path of this
     * URI. This is done by concatenating all but the last segment of this
     * URI's path, if any, with the given URI's path and then normalizing the
     * result as if by invoking the normalize method.
     *
     * The result of this method is absolute if, and only if, either this URI
     * is absolute or the given URI is absolute.
     *
     * For example, resolving the URIs:
     *
     * - <samp>http://www.php.net/manual/en/</samp>
     * - <samp>/manual/en/
     * - <samp>#content</samp>
     * - <samp>language.namespaces.php</samp>
     * - <samp>../fr/language.oop5.interfaces.php</samp>
     *
     * </samp>
     *
     * against the URI <samp>http://www.php.net/manual/en/language.oop5.interfaces.php</samp>
     * will result in the URIs:
     *
     * - <samp>http://www.php.net/manual/en/</samp>
     * - <samp>http://www.php.net/manual/en/</samp>
     * - <samp>http://www.php.net/manual/en/language.oop5.interfaces.php#content</samp>
     * - <samp>http://www.php.net/manual/en/language.namespaces.php</samp>
     * - <samp>http://www.php.net/manual/fr/language.oop5.interfaces.php</samp>
     *
     * respectively.
     *
     * @param HierarchicalUriInterface $uri The URI to be resolved against this URI.
     *
     * @return HierarchicalUriInterface The resulting URI.
     *
     * @link http://tools.ietf.org/html/rfc3986#section-5.4 RFC 3986 § 5.4
     */
    public function resolve(HierarchicalUriInterface $uri);

    /**
     * Relativizes the given URI against this URI.
     *
     * The relativization of the given URI against this URI is computed as
     * follows:
     *
     * If either the scheme and authority components of the two URIs are not
     * identical, or if the path of this URI is not a prefix of the path of the
     * given URI, then the given URI is returned.
     *
     * Otherwise a new relative hierarchical URI is constructed with query and
     * fragment components taken from the given URI and with a path component
     * computed by removing this URI's path from the beginning of the given
     * URI's path.
     *
     * For example, relativizing the URIs:
     *
     * - <samp>http://www.php.net/manual/en/language.namespaces.php</samp>
     * - <samp>http://www.php.net/manual/fr/language.oop5.interfaces.php</samp>
     * - <samp>http://www.php.net/manual/</samp>
     *
     * against the URI <samp>http://www.php.net/manual/en/</samp> will result
     * in the URIs:
     *
     * - <samp>language.namespaces.php</samp>
     * - <samp>../fr/language.oop5.interfaces.php</samp>
     * - <samp>http://www.php.net/manual/</samp>
     *
     * respectively.
     *
     * @param HierarchicalUriInterface $uri The URI to be relativized against this URI.
     *
     * @return HierarchicalUriInterface The resulting URI.
     *
     * @link http://tools.ietf.org/html/rfc3986#section-5.2 RFC 3986 § 5.2
     */
    public function relativize(HierarchicalUriInterface $uri);
}
```

4. Exceptions
-------------

### 4.1 `Psr\Uri\Exception\UnexpectedValueException`

```php
<?php

namespace Psr\Uri\Exception;

use UnexpectedValueException as BaseUnexpectedValueException;

/**
 * Exception thrown if a value does not match with a set of values. Typically
 * this happens when a function calls another function and expects the return
 * value to be of a certain type or value not including arithmetic or buffer
 * related errors.
 */
class UnexpectedValueException extends BaseUnexpectedValueException
{
}
```
