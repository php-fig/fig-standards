Slugifier Interface
===================

This document describes a common interface for slugifier libraries.

The goal is to provide a simple common interface for classes which generate
*slugs*. Such classes are commonly known as *slugifiers* or *urlizers*.

There are many slugifier libraries in existance, each of which handles the
process in a slightly different way.

Frameworks and CMSs that have custom needs MAY extend the interface for their own
purpose, but SHOULD remain compatible with this document. This ensures
that the third-party libraries an application uses can write to the
centralized application logs.

The terms `slugify` refers to the process of transforming a string into a URL-safe 
version of itself.

The key words "MUST", "MUST NOT", "REQUIRED", "SHALL", "SHALL NOT", "SHOULD",
"SHOULD NOT", "RECOMMENDED", "MAY", and "OPTIONAL" in this document are to be
interpreted as described in [RFC 2119][].

The word `implementor` in this document is to be interpreted as someone
implementing the `SligifierInterface` in a slugifier-related library or framework.
Users of slugifiers are refered to as `user`.

[RFC 2119]: http://tools.ietf.org/html/rfc2119

1. Specification
-----------------

### 1.1 Basics

- The `SlugifierInterface` exposes one method, `slugify`, which accepts any
  type of *source string* and returns a *slugified* string, the *return string*.

### 1.2 Source string

 - The implementing class method MUST accept source strings of any description.

### 1.3 Return string

 - The return string must be URL-safe and there for MUST NOT contain any characters
   forbidden in [RFC1738][].

[RFC 1738]: http://tools.ietf.org/html/rfc1738

2. Package
----------

The interface described and a test suite to verify your implementation is provided 
in [psr/slugifier](https://packagist.org/__________) package.

3. `Psr\Log\SlugifierInterface`
-------------------------------

```php
<?php

namespace Psr\Log;

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
    public function slugifiy($string);
}
```
