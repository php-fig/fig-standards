Slugifier Interface
===================

This document describes a common interface for slugifier libraries.

The goal is to provide a simple common interface for classes which generate
URL-safe string, or *slugs*. Such classes are commonly known as *slugifiers*.

There are many slugifier libraries in existence, each of which handles the
process in a slightly different way.

Frameworks and CMSs that have custom needs MAY extend the interface for their own
purpose, but SHOULD remain compatible with this document. This ensures that
third-party libraries can use the same implementation.

The term "URL-safe" implies a string which does not contain any characters forbidden in
[RFC 1738][]. The word "slugify" refers to the process of producing a URL-safe string 
from a given source string.

The key words "MUST", "MUST NOT", "REQUIRED", "SHALL", "SHALL NOT", "SHOULD",
"SHOULD NOT", "RECOMMENDED", "MAY", and "OPTIONAL" in this document are to be
interpreted as described in [RFC 2119][].

[RFC 2119]: http://tools.ietf.org/html/rfc2119
[RFC 1738]: http://tools.ietf.org/html/rfc1738

1. Specification
-----------------

### 1.1 Basics

- The `SlugifierInterface` exposes one method, `slugify`, which accepts a
  *source string* and returns a slugified string - the *return string*.

### 1.2 Source string

 - There MUST NOT be any restrictions on which characters are accepted in the source string.
 - Characters in the source string should not assume any special meaning.

### 1.3 Return string

The return string:

 - MUST be URL-safe.
 - MUST be a string.
 - MAY be an empty string.

2. Package
----------

The interface described and a test suite to verify your implementation is provided 
in [psr/slugifier](https://packagist.org/__________) package.

3. `Psr\Slugifier\SlugifierInterface`
-------------------------------

```php
<?php

namespace Psr\Slugifier;

/**
 * Describes a slugifier instance
 *
 * See _______
 * for the full interface specification.
 */
interface SlugifierInterface
{
    /**
     * Return a URL safe version of a string.
     *
     * @param string $string
     * @return string
     */
    public function slugify($string);
}
```
