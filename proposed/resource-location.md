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

> This proposal is annotated with comments in quote blocks like this one. These
> annotations should help to clarify why certain passages exist and what other
> alternatives exist or have been tried.
>
> The final PSR will not contain these annotations, but a copy could be preserved
> for documentation purposes.
>
> **TL;DR**
>
> This specification proposes to refer to files and directories through URIs, e.g.:
>
> * classpath:/Acme/Demo/Parser/resources/config.yml
> * classpath:/Acme/Demo/Parser/resources
> * classpath:/Acme/Demo/Parser.php
> * file:/var/www/project/favicon.ico
> * view:/acme/demo-package/show.php
>
> This URIs can have different schemes ("classpath", "file" etc.), but only the
> scheme "file" is specified in this document.
>
> The resource locator is able to turn URIs into file paths which can be read
> or included by PHP code, e.g.:
>
> ```php
> // autoloading
> include $locator->findResource('classpath:/Acme/Demo/Parser.php');
>
> // loading of configuration files
> $config = Yaml::parse($locator->findResource('classpath:/Acme/Demo/config.yml'));
>
> // loading of templates
> // internally prepends the "view:" scheme
> // loads "show.php" from the Composer package "acme/demo-package"
> render('/acme/demo-package/show.php');
> ```
>
> How to configure such a resource locator depends on the implementation.
> A very simple implementation would feature a method `addPath()` which maps a
> URI scheme and a URI path prefix to a directory:
>
> ```php
> // paths in the "classpath" scheme are prefixed by PHP namespaces with
> // forward slashes
> $locator->addPath('classpath', '/Acme/Demo/', '/path/to/src');
>
> // paths in other schemes (except for "file") are prefixed by the
> // Composer package name (by convention, not enforced)
> $locator->addPath('view', '/acme/demo-package/', '/path/to/resources/views');
> ```
>
> **Main Goals:**
>
> The general goal of this PSR is to locate files (PHP, XML, YAML, INI, JPG, etc.)
> and directories in a generic way. For example, there should be a unified
> notation to refer to *the file* of a class `\A\B\C\D` and other files located in
> the same directory (or nested directories).
>
> The secondary goal is to locate files in redistributable (e.g. Composer)
> packages in a generic fashion.
>
> The tertiary goal is to provide a foundation for the new autoloader PSR.
>
> **Requirements:**
>
> 1. **Locate files relative to classes**
>
>    If a file is in the same directory (or a subdirectory of) as a class
>    `\A\B\C\D`, allow to locate its path by providing (a) the namespace
>    `\A\B\C\` and (b) the relative location of the file, e.g.
>    `config/settings.yml`.
>
>    ```php
>    $locator->findResource('classpath:/Acme/Demo/config/settings.yml');
>    ```
>
> 2. **Locate both directories and files**
>
>    ```php
>    $locator->findResource('classpath:/Acme/Demo/config/settings.yml');
>    $locator->findResource('classpath:/Acme/Demo/config');
>    ```
>
> 3. **Short identifiers when the context is known**
>
>    If similar files are always located in the same location, provide
>    a short, redundancy-free notation. For example, if a namespace
>    is `\A\B\C\` and template files are always located in a subdirectory
>    `resources/views/`, make it possible to skip this redundant information.
>
>    ```twig
>    {% include 'classpath:/Acme/Demo/resources/views/show.html.twig' %}
>    {% include 'view:/acme/demo-package/show.html.twig' %}
>    {% include '/acme/demo-package/show.html.twig' %}
>
> 4. **Locate resources independent from PHP files**
>
>    Even though this is the main goal, support location of resources
>    independent from PHP source code.
>
>    ```php
>    $locator->addPath('view', '/app/', '/path/to/app/views');
>    $locator->findResource('view:/app/layout.php');
>    ```
>
> 5. **Support resource overriding**
>
>    Look for a resource in multiple directories to support overriding.
>
>    ```php
>    $locator->addPath('classpath', '/Acme/Demo/', array(
>        '/path/to/overridden/src',
>        '/path/to/acme/demo/src',
>    ));
>
>    include $locator->findResource('classpath:/Acme/Demo/Parser.php');
>
> **Future Outlook**:
>
> This PSR addresses only (a) a way for identifying resources through URIs
> and (b) a way for locating these resources through the resource locator.
> Apart from "file", no concrete URI schemes (e.g. "classpath", "view" etc.)
> are specified. These could be added either to this or to a separate
> PSR.
>
> Once the "classpath" scheme is specified, the autoloader PSR is reduced
> to turning a class request into a URI and locating that URI with the
> locator.
>
> **Performance**:
>
> Resource location performance can be optimized by mirroring the URI
> paths in a cache directory. Then location is reduced to one simple
> `file_exists()` check in that cache directory. For example, the
> URIs
>
> * view:/acme/demo/show.php
> * classpath:/Acme/Demo/Parser.php
>
> would be cached through symbolic links in
>
> ```
> /cache
>     /view
>         /acme
>             /demo
>                 show.php -> /path/to/show.php
>     /classpath
>         /Acme
>             /Demo
>                 Parser.php -> /path/to/Parser.php
> ```

1. Specification
----------------

### 1.1 Definitions

- **Resource**: A common file or directory.

### 1.2 Resource URIs

Resources are identified by URIs that MUST conform to
[RFC 3986](http://tools.ietf.org/html/rfc3986), with the following restrictions.

> An alternative to URIs was proposed by @simensen. He proposed to locate
> resources through typical namespaced PHP identifiers, e.g.
>
> * \Acme\Demo\resources\views\show.php
>
> The disadvantage is that this method does not allow the same extensibility
> that URI schemes ("classpath:/", "view:/", "config:/" etc.) do. In
> particular, the requirements 3 and 4 from above cannot be fulfilled.
>
> The advantage of URIs is that we can use PHP's native functions such as
> `parse_url()` or `dirname()` to work with them.

Resource URIs MUST contain at least a non-empty scheme and a non-empty path.
Additional URI parts MAY be interpreted by implementors, but their effect is
undefined by this specification.

> i.e. scheme:/some/path
>
> Other URI parts are the authority (host:port) or the query string. We don't
> need them for the base functionality, but their presence doesn't hurt either.

The path of a resource URI SHOULD start with a slash ("/").

> Reason: The distinction between absolute and relative paths must be possible
> if the scheme is omitted, e.g. "Demo/Parser.php" vs. "/Demo/Parser.php".

Valid path segments consist of alphanumeric characters (`A-Z`, `a-z`, `0-9`),
underscores (`_`), hyphens (`-`), colons (`:`) and dots (`.`). Implementors MAY
choose to support additional characters, but interoperability is not guaranteed
for such URIs.

> This prevents problems with invalid characters for file names, e.g. "*" or
> "<" on NTFS.
>
> Should percent encoding (%20 etc.) be allowed, as defined in the RFC?

Paths MUST NOT contain dot segments (`.` and `..`).

> For security reasons.

The path structure MAY be further restricted by specifications of specific
schemes (for example the "file" scheme in section 1.5).

> E.g. the "classpath" scheme requires path prefixes to correspond to PHP
> namespaces with backslashes replaced by forward slashes.
>
> E.g. "\Acme\Demo\" -> "classpath:/Acme/Demo/config.yml"

Examples of valid URIs:

- classpath:/Acme/Demo/Parser.php
- view:/acme/demo-package/template.php
- config:/acme/demo-package
- file:/
- file:C:/Project/settings.xml

### 1.3 Resource Variants

The main task of the resource locator is to resolve resource URIs to file paths.
Each resource URI MAY resolve to multiple file paths. These are called
*resource variants*.

> For overriding resources. See requirement 5.

The `ResourceLocatorInterface` exposes the method `findResourceVariants()` to
retrieve the variants. The method receives a resource URI as first argument and
MUST throw a `Psr\ResourceLocation\IllegalUriException` if the URI does not
correspond to the rules described in section 1.2.

`findResourceVariants()` MUST return an array which MUST be empty or contain
only strings, i.e. the resource variants.

Each resource variant MUST be an absolute path and MUST exist on the local
file system.

> Files must exist, otherwise the validity of resource variants cannot be
> determined. For example:
>
> ```php
> $locator->addPath('classpath', '/Acme/Demo/', array(
>     '/path/to/overridden/src',
>     '/path/to/acme/src',
> ));
>
> // returns /path/to/overridden/src/Parser.php if it exists,
> // /path/to/acme/src/Parser.php otherwise
> $locator->findResource('classpath:/Acme/Demo/Parser.php');
> ```
>
> As for the locality of files, I considered allowing to return
> [other valid PHP streams](http://at2.php.net/manual/en/wrappers.php) as well, 
> but most of them don't work on default configurations or are restricted by
> allow_url_(fopen|include). The schemes that are not restricted are
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

> For clarification only. Not sure where this might be useful, but I'm also
> not sure that it never will be.

`findResourceVariants()` MUST return an the same variants in the same order
when called multiple times during the execution of a PHP application. The
order MAY be chosen by the implementor.

> Idempotence.

### 1.4 Resource Location

The `ResourceLocatorInterface` exposes the method `findResource()` for
resolving a resource URI to a file path. It receives a resource URI as first
argument and MUST throw a `Psr\ResourceLocation\IllegalUriException`
if the URI does not correspond to the rules described in section 1.2.

`findResource()` MUST return a string, which MUST be equivalent to the first
entry of the array returned by `findResourceVariants()`. If no existing path
can be found, a `Psr\ResourceLocation\NoSuchResourceException` MUST be thrown.

> The variant is the highest priority one.

### 1.5 File Scheme

Implementations of this PSR MUST support the scheme "file". The URI path MUST
then correspond to a path on the local file system, although directory
separators MUST be written as slashes (`/`) in the URI. For example, the
URI `file:C:/Project/settings.xml` resolves to either `C:/Resources/settings.xml`
or `C:\Resources\settings.xml`, depending on the locator implementation.

> For generic use cases (hacks) that cannot be achieved with other schemes.

`findResourceVariants()` MUST return an empty array if the path specified by
a URI in the "file" scheme does not exist. It MUST return an array with exactly
one entry if the path exists. This entry MUST be the path itself.

> Consistency with previous sections.

`findResource()` MUST return the path of a URI in the "file" scheme if it
exists. Otherwise a `Psr\ResourceLocation\NotFoundException` MUST be thrown.

> Consistency with previous sections.

2. Package
----------

The described interface as well as relevant exception classes and a test suite
to verify your implementation is provided as part of the psr/resource-location
package.

3. ResourceLocatorInterface
---------------------------

> TODO: Will be elaborated once there is agreement on the general direction.

```php
<?php

namespace Psr\ResourceLocation;

interface ResourceLocatorInterface
{
    public function findResource($uri);

    public function findResourceVariants($uri);
}
```
