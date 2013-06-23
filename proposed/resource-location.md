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
> * classpath:///Acme/Demo/Parser/resources/config.yml
> * classpath:///Acme/Demo/Parser/resources
> * classpath:///Acme/Demo/Parser.php
> * file:///var/www/project/favicon.ico
> * view:///acme/demo-package/show.php
>
> These URIs can have different schemes ("classpath", "file" etc.), but only the
> schemes "file" and "classpath" are explicitly specified in this document.
>
> The resource locator is able to turn URIs into file paths which can be read
> or included by PHP code, e.g.:
>
> ```php
> // autoloading
> include $locator->findResource('classpath:///Acme/Demo/Parser.php');
>
> // loading of configuration files
> $config = Yaml::parse($locator->findResource('classpath:///Acme/Demo/config.yml'));
>
> // loading of templates
> // internally prepends the "view://" scheme
> // loads "show.php" from the Composer package "acme/demo-package"
> render('/acme/demo-package/show.php');
> ```
>
> How to configure such a resource locator depends on the implementation.
> A very simple implementation would feature a method `addPath()` which maps a
> URI scheme and a URI path prefix to one or more directories:
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
>    $locator->findResource('classpath:///Acme/Demo/config/settings.yml');
>    ```
>
> 2. **Locate both directories and files**
>
>    ```php
>    $locator->findResource('classpath:///Acme/Demo/config/settings.yml');
>    $locator->findResource('classpath:///Acme/Demo/config');
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
>    {% include 'classpath:///Acme/Demo/resources/views/show.html.twig' %}
>    {% include 'view:///acme/demo-package/show.html.twig' %}
>    {% include '/acme/demo-package/show.html.twig' %}
>    ```
>
> 4. **Locate resources independent from PHP classes**
>
>    Even though this is not the main goal, support location of resources
>    independent from PHP classes, interfaces and traits.
>
>    ```php
>    $locator->addPath('view', '/app/', '/path/to/app/views');
>    $locator->findResource('view:///app/layout.php');
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
>    include $locator->findResource('classpath:///Acme/Demo/Parser.php');
>
> **Performance**:
>
> Resource location performance can be optimized by mirroring the URI
> paths in a cache directory. Then location is reduced to one simple
> `file_exists()` check in that cache directory. For example, the
> URIs
>
> * view:///acme/demo/show.php
> * classpath:///Acme/Demo/Parser.php
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
>
> **Common Rules of Resource Location and Autoloading**
>
> Since both the autolading PSR (PSR-X) and this PSR (PSR-R) define how
> to map PHP namespaces to directories, and how to locate files in these
> directories, they need common logical rules for specifying this mapping.
>
> We have the following possibilities:
>
> 1. Include common rules in PSR-R, refer to PSR-R from PSR-X
> 2. Include common rules in PSR-X, refer to PSR-X from PSR-R
> 3. Include common rules in both
> 4. Move common rules to a separate PSR, refer to that PSR from both
>    PSR-X and PSR-R
>
> I want to briefly outline the implications of these solutions:
>
> **1. Include common rules in PSR-R, refer to PSR-R from PSR-X**
>
> In this case, the logical formulation of the PSRs will essentially be:
>
> PSR-R: Given the URI "classpath:///A/B/C/D" and a prefix `/A/B` mapped
> to some path `/src`, then `/src/C/D` must be an existing *directory or
> file*. If `/src/C/D` is a file with PHP class definitions, one of them must
> have the FQCN `\A\B\C\D`.
>
> PSR-X: The autoloader must turn the loaded class into a PSR-R compatible
> classpath URI (trivial), use the PSR-R locator to find its path and include
> that path.
>
> Advantages:
>
> * (comparatively) simple logical constructs
> * simple implementation
>
> Disadvantages:
>
> * PSR-X will be delayed after PSR-R
> * PSR-X cannot be implemented without either
>   - using a `ResourceLocatorInterface` instance, or
>   - understanding and (partially) implementing PSR-R
>
> Implementing an autoloader is then as simple as:
>
> ```php
> spl_autoload_register(function ($class) use ($locator) {
>     try {
>         include $locator->findResource('classpath:///'.strtr($class, '\\', '/').'.php');
>     } catch (\Exception $e) {
>     }
> });
> ```
>
> **2. Include common rules in PSR-X, refer to PSR-X from PSR-R**
>
> In this case, the logical formulation of the PSRs will essentially be:
>
> PSR-X: Given a FQCN `\A\B\C\D` and a prefix `\A\B` mapped to some path
> `/src`, then `/src/C/D/` must be a file containing PHP class definitions.
> One of these definitions must have the FQCN `\A\B\C\D`.
>
> PSR-R: Given the URI "classpath:///A/B/C/D", then one of the prefixes, when
> turned into a namespace, must be mapped by PSR-X to some path `/src`. Then
> `/src/C/D` must be an existing *directory or file*. If `/src/C/D` is a file
> with PHP class definitions, then autoloading `\A\B\C\D` per PSR-X must result
> in loading `/src/C/D`.
>
> Advantages:
>
> * PSR-X can be released now
> * PSR-X can be implemented independently from whether PSR-R is successful or not
>
> Disadvantages:
>
> * PSR-R will be more complicated to formulate (see the example above)
> * if it turns out that today's formulation of PSR-X is not adequate/sufficient
>   for PSR-R, we will either have to
>   - release a suboptimal PSR-R spec or
>   - release a PSR-R spec that is incompatible with PSR-X
>
> **3. Include common rules in both**
>
> Advantages:
>
> * PSR-X can be released now
> * PSR-X can be implemented independently from whether PSR-R is successful or not
>
> Disadvantages:
>
> * PSR-X and PSR-R implementations are not necessarily compatible
> * if it turns out that today's formulation of PSR-X is not adequate/sufficient
>   for PSR-R, we will either have to
>   - release a suboptimal PSR-R spec or
>   - release a PSR-R spec that is incompatible with PSR-X
>
> **4. Move common rules to a separate PSR, refer to that PSR from both
> PSR-X and PSR-R**
>
> Advantages:
>
> * PSR-X can be implemented independently from whether PSR-R is successful or not
>
> Disadvantages:
>
> * PSR-X is delayed after that separate PSR
> * three different PSRs for resource location and autoloading might confuse people

Resource Location
=================

This document describes a common interface for resource location in PHP.

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

**Implementation**: An implementation of `Psr\ResourceLocation\ResourceLocatorInterface`.

**Consumer**: Code using a `Psr\ResourceLocation\ResourceLocatorInterface` implementation.

**Resource**: A common file or directory.

**Path Segment**: A path segment as defined by
[RFC 3986](http://tools.ietf.org/html/rfc3986), without leading or trailing
slash ("/").

**Path**: A sequence of zero or more path segments, separated by slashes
and starting with a slash. `/`, `/A`, `/A/` and `/A/B` are valid paths.

**Path Prefix**: Zero or more contiguous path segments that appear at the start
of a path, including the leading but excluding the trailing slash. Given
a path `/A/B/C/D`, the possible path prefixes are `/`, `/A`, `/A/B`, `/A/B/C`
and `/A/B/C/D`.

> If /A/B/C/D is mapped to /path/to/D, then
>
> * classpath:///A/B/C/D
>
> should resolve to /path/to/D. To keep the definition of the classpath scheme
> simple - which requires that at least one path prefix must be mapped - the
> full path is also considered a possible prefix.

**Relative Path**: Given a path `/A/B/C/D` and a prefix `/A/B`, the relative
path is `C/D`. Relative paths never start with a slash ("/").

**Class**: A PHP class, trait or interface.

**Fully Qualified Class Name (FQCN)**: A class identifier given as fully
qualified name as defined by the
[PHP Name Resolution Rules](http://php.net/manual/en/language.namespaces.rules.php).

**Namespace**: Given a FQCN `\A\B\C\D`, the namespace of that class is `\A\B\C`.

### 1.2 Resource URIs

Resources are identified by URIs that MUST conform to
[RFC 3986](http://tools.ietf.org/html/rfc3986), with the following restrictions.

> An alternative to URIs was proposed by @simensen. He proposed to locate
> resources through typical namespaced PHP identifiers, e.g.
>
> * \Acme\Demo\resources\views\show.php
>
> The disadvantage is that this method does not allow the same extensibility
> that URI schemes ("classpath://", "view://", "config://" etc.) do. In
> particular, the requirements 3 and 4 from above cannot be fulfilled.
>
> The advantage of URIs is that we can use PHP's native functions such as
> `dirname()` or `basename()` to work with them.

Resource URIs MUST contain at least a non-empty scheme, followed by a colon
(":"), a double slash ("//") and a non-empty path. Additional URI parts MAY be
interpreted by implementors, but their effect is undefined by this specification.

> i.e. scheme:///some/path
>
> Other URI parts are the authority (host:port) or the query string. We don't
> need them for the base functionality, but their presence doesn't hurt either.
>
> We have the alternatives of including or excluding the double slash after the
> scheme:
>
> * scheme:///some/path vs.
> * scheme:/some/path
>
> Both are valid URIs. The disadvantage of the latter is that PHP Stream
> Wrappers are not supported for it.
>
> "The URL syntax used to describe a wrapper only supports the scheme://...
> syntax. The scheme:/ and scheme: syntaxes are not supported."
> – http://www.php.net/manual/en/wrappers.php

The path of a resource URI SHOULD start with a slash ("/").

> Reason: The distinction between absolute and relative paths must be possible
> if the scheme is omitted, e.g. "Demo/Parser.php" vs. "/Demo/Parser.php".
>
> SHOULD and not MUST in order to support absolute Windows paths properly:
> file://C:/some/path

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
> E.g. "\Acme\Demo\" -> "classpath:///Acme/Demo/config.yml"

Examples of valid URIs:

- classpath:///Acme/Demo/Parser.php
- view:///acme/demo-package/template.php
- config:///acme/demo-package
- file:///
- file://C:/Project/settings.xml

### 1.3 Resource Variants

The main task of the resource locator is to resolve resource URIs to existing
file paths. These are called *resource variants*. Each resource URI MAY resolve
to multiple variants. The concrete definition of how to obtain a variant for
a given URI is provided by scheme specifications (section 1.5 and 1.6).

> For overriding resources. See requirement 5.

The `ResourceLocatorInterface` exposes the method `findResourceVariants()` which
receives a resource URI as first argument and MUST return all variants for that
URI in descending order of priority. How to assign priorities MUST be chosen by
the implementation (e.g. FIFO). The method MUST throw a
`Psr\ResourceLocation\IllegalUriException` if the URI does not correspond to the
rules described in section 1.2.

> If a scheme specification defines what a "variant" is for a given URI, by
> implication every such variant must be returned by findResourceVariants().

`findResourceVariants()` MUST return an array which MUST contain only strings,
i.e. the resource variants. If no variants exist for a resource URI, the array
MUST be empty.

> No other return values allowed.
>
> If a scheme specification defines what a "variant" is but no such variant
> can be found for a given URI, by implication an empty array must be returned.

Each resource variant MUST be an absolute path or a URI and MUST exist on the
local file system. If a variant is given as URI, it MUST have one of the
[following schemes available in PHP](http://php.net/manual/en/wrappers.php):

* file://
* phar://
* zlib://
* zip://
* bzip2://

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
> $locator->findResource('classpath:///Acme/Demo/Parser.php');
> ```
>
> As for the URI schemes, these are the only ones that are not restricted by
> allow_url_(fopen|include). Another one would be "glob://", which is not
> guaranteed to deliver a result. Certain variants of "php://" are also not
> restricted, but I'm not sure whether they should be allowed (e.g.
> "php://stdout").

Different resource URIs MAY be resolved to the same resource variants. They
MAY even be resolved to overlapping sets of variants, although this is NOT
RECOMMENDED. Two sets of variants are *overlapping* if they contain both
common and distinct variants. For example, the sets {V1, V2} and {V2, V3} are
overlapping.

> For clarification only. Not sure where this might be useful, but I'm also
> not sure that it never will be.

`findResourceVariants()` MUST return the same variants in the same order
when called multiple times during the execution of a PHP application.

> Idempotence.

### 1.4 Resource Location

The `ResourceLocatorInterface` exposes the method `findResource()` for
resolving a resource URI to a resource variant. It receives a resource URI as
first argument and MUST throw a `Psr\ResourceLocation\IllegalUriException`
if the URI does not correspond to the rules described in section 1.2.

`findResource()` MUST return a string, which MUST be equivalent to the first
entry of the array returned by `findResourceVariants()`. If no existing path
can be found, a `Psr\ResourceLocation\NoSuchResourceException` MUST be thrown.

> The first variant is the highest priority one, as defined in section 1.3.

### 1.5 File Scheme

> For generic use cases (hacks) that cannot be achieved with other schemes.

Implementations of this PSR MUST support the scheme "file". If the URI path
corresponds to an existing path on the local file system, it MUST be considered
a resource variant for the given URI.

> Once it is defined what a resource variant is for the file scheme, the
> rules in section 1.3 and 1.4 guarantee that the locator behaves correctly.

### 1.6 Classpath Scheme

Implementations of this PSR MUST support the scheme "classpath". The URI path
MUST then begin with a slash ("/"), followed by a top-level path segment (the
*vendor namespace*), which MUST be followed by zero or more sub-path segments.
Implementations MAY choose not to validate this rule.

> Valid examples:
>
> * classpath:///Acme
> * classpath:///Acme/Demo
> * classpath:///Acme/Demo/Parser.php
> * classpath:///Acme/Demo/config
> * classpath:///Acme/Demo/config/settings.ini

At least one path prefix of the URI path MUST be mapped to a directory
(the *base directory*) provided as absolute path or URI with one of the
schemes defined in section 1.3. That directory MUST exist on the local file
system, although implementations MAY choose not to validate this rule. A path
prefix MAY be mapped to more than one directory. How the mapping is specified
MUST be chosen by the implementation.

> Valid examples:
>
> * / => /path/to/src/
> * /Acme => /path/to/acme/
> * /Acme => phar://acme.phar/src
> * /Acme => [/path/to/acme/, /path/to/overridden/]
> * /Acme/Demo/Parser => /path/to/src/
> * /Acme/Demo/Parser.php => /path/to/src/ (valid, but not sensible in practice)
>
> Invalid examples (mapping of files is not supported):
>
> * /Acme/Demo/Parser.php => /path/to/src/Parser.php

Given a URI path `/A/B/C` that consists of the path prefixes {`/`, `/A`, `/A/B`,
`/A/B/C`} and the corresponding relative paths {`A/B/C`, `B/C`, `C` and `<empty>`},
the resulting string MUST be the concatentation of a base directory mapped to one
of the prefixes and the corresponding relative path, separated by a slash. If that
string is an existing path in the file system, it MUST be considered a resource
variant for the given URI.

> For example, if `/A/B` is mapped to `/src`, and the path `/src/C` exists, then
> `/src/C` is a valid resource variant for the URI `classpath:///A/B/C`.
>
> Once it is defined what a resource variant is for the classpath scheme, the
> rules in section 1.3 and 1.4 guarantee that the locator behaves correctly.

Variants for longer path prefixes MUST have a higher priority than variants for
shorter path prefixes.

> If both `/A` and `/A/B` are mapped, files found in a base directory mapped to
> `/A/B` should be preferred.

If a resource variant contains PHP class definitions that would be loaded when
[including](http://php.net/manual/en/function.include.php) the file, exactly
one of these classes MUST have a FQCN equivalent to the URI path with all
slashes ("/") replaced by backslashes ("\") and the file extension(s) removed.
This is the *primary class*. All other classes in the file MUST belong to the
same namespace as the primary class. The implementation MAY choose not to
validate this rule.

> Make sure that when mapping
>
> * /Acme/Demo/Parser.php => /path/to/src/Parser.php
>
> then Parser.php must contain the class \Acme\Demo\Parser.
>
> If it contains further classes, these must belong to the \Acme\Demo\
> namespace.
>
> "PHP class definitions" are defined using include to avoid restrictions on
> the file extensions (".php", ".php5" etc.) or similar.

### 1.7 Further Schemes

Further schemes MAY freely be added by implementations, consumers and future
PSRs. However, the following schemes SHOULD be used for the corresponding
resource types listed in the table:

| Scheme     | Resource Type                             |
|------------|-------------------------------------------|
| config://  | configuration files                       |
| doc://     | documentation files                       |
| lang://    | translation files                         |
| public://  | CSS, JS, images and similar public files   |
| view://    | template/view files                       |

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
