Resource Location
=================

This document describes a common interface for locating resources in PHP.

The main goal is to allow libraries to receive a
`Psr\ResourceLocation\ResourceLocatorInterface` object and locate file resources
in a simple and universal way. Frameworks and CMSs that have custom needs MAY
extend the interface for their own purpose, but SHOULD remain compatible with
this document. This ensures that the third-party libraries an application uses
can locate resources as specified in this document.

The key words "MUST", "MUST NOT", "REQUIRED", "SHALL", "SHALL NOT", "SHOULD",
"SHOULD NOT", "RECOMMENDED", "MAY", and "OPTIONAL" in this document are to be
interpreted as described in [RFC 2119](http://tools.ietf.org/html/rfc2119).

1. Specification
----------------

### 1.1 Definitions

- **Resource**: A common file or directory.

### 1.2 Resource URIs

> Goals:
>
> * Locate absolute file and directory paths embedded in PHP applications/packages
> * Extensibility for other cases (remote file systems)
> * Security by preventing `.` and `..`

Resources are identified by URIs that MUST conform to
[RFC 3986](http://tools.ietf.org/html/rfc3986), with the following restrictions.

> An alternative was suggested by

Resource URIs MUST contain at least a non-empty scheme and a non-empty path.
Additional URI parts MAY be interpreted by implementors, but their effect is
undefined by this specification.

The path of a resource URI SHOULD start with a slash ("/").

> Reason: The distinction between absolute and relative paths must be possible
> if the scheme is omitted, e.g. "Demo/Parser.php" vs. "/Demo/Parser.php".

Valid path segments consist of alphanumeric characters (`A-Z`, `a-z`, `0-9`),
underscores (`_`), hyphens (`-`), colons (`:`) and dots (`.`). Implementors MAY
choose to support additional characters, but interoperability is not guaranteed
for such URIs.

> Allow percent encoding?

Paths MUST NOT contain dot segments (`.` and `..`).

> For security reasons.

The path structure MAY be further restricted by specifications of specific
schemes (for example the "file" scheme in section 1.5).

> E.g. the "classpath" scheme requires path prefixes to correspond to PHP
> namespaces with backslashes replaced by forward slashes.

Examples of valid URIs:

- `classpath:/Acme/Demo/Parser.php`
- `view:/acme/demo-package/template.php`
- `config:/acme/demo-package`
- `file:/`
- `file:C:/Project/settings.xml`

### 1.3 Resource Variants

The main task of the resource locator is to resolve resource URIs to file paths.
Each resource URI MAY resolve to multiple file paths. These are called
*resource variants*.

The `ResourceLocatorInterface` exposes the method `findResourceVariants()` to
retrieve the variants. The method receives a resource URI as first argument and
MUST throw a `Psr\ResourceLocation\IllegalUriException` if the URI does not
correspond to the rules described in section 1.2.

`findResourceVariants()` MUST return an array which MUST be empty or contain
only strings, i.e. the resource variants.

Each resource variant MUST be an absolute path and MUST exist on the local
file system.

> I considered accepting [other PHP streams](http://at2.php.net/manual/en/wrappers.php)
> except for plain local files, but most of them don't work on default
> configurations or are restricted by allow_url_(fopen|include). The schemes
> that are not restricted are
>
> * php://
> * zlib://
> * glob://
> * phar://
>
> None of them make sense IMO in the context of resource locating, but I'll
> be convinced otherwise if you think they do.

Different resource URIs MAY be resolved to the same resource variants. They
MAY even be resolved to overlapping sets of variants, although this is NOT
RECOMMENDED. Two sets of variants are overlapping if they contain both common
and distinct variants. For example, the sets {V1, V2} and {V2, V3} are
overlapping.

`findResourceVariants()` MUST return an the same variants in the same order
when called multiple times during the execution of a PHP application. The
order MAY be chosen by the implementor.

### 1.4 Resource Location

The `ResourceLocatorInterface` exposes the method `findResource()` for
resolving a resource URI to a file path. It receives a resource URI as first
argument and MUST throw a `Psr\ResourceLocation\IllegalUriException`
if the URI does not correspond to the rules described in section 1.2.

`findResource()` MUST return a string, which MUST be equivalent to the first
entry of the array returned by `findResourceVariants()`. If no existing path
can be found, a `Psr\ResourceLocation\NoSuchResourceException` MUST be thrown.

### 1.5 File Scheme

Implementations of this PSR MUST support the scheme "file". The URI path MUST
then correspond to a path on the local file system, although directory
separators MUST be written as slashes (`/`) in the URI. For example, the
URI `file:C:/Project/settings.xml` resolves to either `C:/Resources/settings.xml`
or `C:\Resources\settings.xml`, depending on the locator implementation.

> For generic use cases that cannot be achieved with other schemes.

`findResourceVariants()` MUST return an empty array if the path specified by
a URI in the "file" scheme does not exist. It MUST return an array with exactly
one entry if the path exists. This entry MUST be the path itself.

`findResource()` MUST return the path of a URI in the "file" scheme if it
exists. Otherwise a `Psr\ResourceLocation\NotFoundException` MUST be thrown.

2. Package
----------

The described interface as well as relevant exception classes and a test suite
to verify your implementation is provided as part of the psr/resource-location
package.

3. ResourceLocatorInterface
---------------------------

```php
<?php

namespace Psr\ResourceLocation;

interface ResourceLocatorInterface
{
    public function findResource($uri);

    public function findResourceVariants($uri);
}
```
