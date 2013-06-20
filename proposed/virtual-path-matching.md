> This proposal is annotated with comments in quote blocks like this one. These
> annotations should help to clarify why certain passages exist and what other
> alternatives exist or have been tried.
>
> The final PSR will not contain these annotations, but a copy could be preserved
> for documentation purposes.
>
> **TL;DR**
>
> Finds actual paths on a file system for logical paths, such as FQCNs
> ("\Acme\Demo\Parser") or URI paths ("/acme/demo-package/show.html.php"),
> using a mapping of logical paths to file system paths. The separator
> character can be chosen by the implementation.
>
> Example:
>
> ```php
> $sep = '\\';
> $mapping = array(
>    '\\Acme\\Blog\\' => 'src/blog',
>    '\\Acme\\Demo\\Parser.php' => 'src/Parser.php',
> );
>
> echo match_path('\\Acme\\Blog\\ShowController.php', $mapping, $sep);
> // => "src/blog/ShowController.php"
>
> echo match_path('\\Acme\\Demo\\Parser.php', $mapping, $sep);
> // => "src/Parser.php"
> ```
>
> The algorithm does not care about file suffixes or the distinction between
> files and directories. Restrictions in this regard can be made by PSRs
> using this algorithm (i.e. PSR-X, PSR-R and others).
>
> **Potential formulation of PSR-X (autoloading) based on this PSR**
>
> - A FQCN MUST begin with a top-level namespace name (the *vendor namespace*),
>   which MUST be followed by zero or more sub-namespace names, and MUST end in
>   a class name.
> 
> - The path matching algorithm described in PSR-? MUST be used to find a
>   matching file for a FQCN, using a backslash ("\") as separator. The input
>   for the algorithm MUST be the FQCN suffixed with `.php`.
> 
> - If a matching file was found, the registered autoloader MUST include or
>   require it.
>
> - The registered autoloader callback MUST NOT throw exceptions, MUST NOT
>   raise errors of any level, and SHOULD NOT return a value.
>
> **Potential formulation of a PSR-R (resource location) based on this PSR**
>
> - Implementations of this PSR MUST support the scheme "classpath". The path
>   matching algorithm described in PSR-? MUST then be used to find matching
>   files for the URI path, using a slash ("/") as separator. Each match MUST
>   be considered a resource variant for the given URI.
>
> - Consumers using both a PSR-X compliant autoloader and a PSR-R compliant
>   resource locator SHOULD pass the same path mapping to this algorithm as is
>   used in the PSR-X autoloader, with backslashes ("\") in the mapped paths
>   replaced by forward slashes ("/").

Path Matching
=============

This document describes an algorithm that finds the file system path(s) for a
logical path.

The main goal is to provide a foundation for future PSRs based on this
algorithm, such as an autoloader PSR, a resource location PSR and so on.

The key words "MUST", "MUST NOT", "REQUIRED", "SHALL", "SHALL NOT", "SHOULD",
"SHOULD NOT", "RECOMMENDED", "MAY", and "OPTIONAL" in this document are to be
interpreted as described in [RFC 2119](http://tools.ietf.org/html/rfc2119).

1. Specification
----------------

### 1.1 Definitions

**Path Matcher**: A program implementing the path matching algorithm described
in section 1.2.

> This should neither be fixed to PHP code, nor C code, nor a method, nor a
> function. "Program" is a generic term that matches all of these concepts.

**Separator**: A single character chosen by the path matcher, for example a
slash ("/").

> Allows to use this algorithm for both autoloading (separator: "\") and
> resource location (separator: "/").

**Path Segment**: A sequence of one or more characters except for separators.

**(Logical) Path**: A sequence of zero or more path segments, divided by
separators and starting with a separator. Given the separator "/", then `/`,
`/A`, `/A/` and `/A/B` are valid paths.

**File System Path**: A path to a file or directory on the file system.

> E.g. a namespace (\Acme\Demo\Parser) or a URI path (/acme/demo-package/config)

**Path Prefix**: Given a path, then a path prefix is any prefix of the path
that ends with a separator. For example, given the separator "/" and the path
`/A/B/C`, then `/`, `/A/` and `/A/B/` are valid path prefixes.

**Relative Path**: Given a path and one of its path prefixes, then the relative
path is the remaining part of that path. For example, given the separator
"/", a path `/A/B/C/D` and a path prefix `/A/B/`, then `C/D` is the relative path.

**Path Mapping**: A set of logical paths, each of which is associated with one
or more file system paths. Given a path mapping, we call the logical paths in the
mapping *mapped*. We refer to the file system paths in the mapping as *base paths*.
The base paths MUST be provided such that PHP can read and include them from the
local file system.

> That is, the base paths must be loadable even if allow_url_fopen and
> allow_url_include are disabled.

### 1.2 Path Matching Algorithm

Compliant path matchers MUST implement this algorithm or an equivalent
algorithm that returns the same outputs for the same inputs.

> For example, a caching algorithm is implemented differently but behaves
> the same.

Given the separator "/", a path `/A/B/C/D` and a path mapping which associates
`/A/B/C/D` with one or more base paths, then every base path is a *potential
match*.

> The full path itself can be mapped. Needed in the resource location PSR to
> look up the directories that a namespace is mapped to.

Given the separator "/", a path `/A/B/C/D`, the path prefix `/A/B/`,
the relative path `C/D` and a path mapping which associates `/A/B/` with the base
path `/src`, then a potential match is generated by concatenating the base
path, a directory separator and the relative path: `/src/C/D`.

Separators in potential matches MUST be replaced by directory separators.

> Allows the use of other separators than slashes, e.g. backslashes ("\").

A potential match `/src/C/D` is *evaluated* by checking whether it exists on
the local file system. If it does, it is called a *match*.

> Matches must exist.

If both a full path and any of its prefixes are mapped, potential matches
for the full path MUST be evaluated before those for the prefixes. If a mapped
path contains multiple mapped prefixes, potential matches for longer prefixes
MUST be evaluated before those for shorter prefixes. 

> Define order of evaluation..

If a prefix is mapped to multiple base paths, the potential matches MUST be
evaluated in the order of the base paths.

> When multiple implementations of this algorithm (e.g. PSR-X and PSR-R)
> receive the same mapping, they should behave identically.

A path matcher MAY choose to abort this algorithm once a match has been found,
or continue in order to generate all matches.

> Find first vs. find all matches.

A path matcher MAY not find a match for a logical path. The result in this
case is undefined.

> Exception, returning null etc. can be chosen by the implementation.

### 1.3 EBNF

The following block defines the path syntax used in this PSR using the Extended
Backus-Naur Form (EBNF) specified in ISO/IEC 14977.

```
separator     = chosen separator character
path-symbol   = all characters - separator
path-segment  = path-symbol, {path-symbol}
path-prefix   = separator, {path-segment, separator}
relative-path = path-segment, {separator, path-segment}
path          = path-prefix, [relative-path]
```

2. Package
----------

The test suite to verify a path macher implementation is provided as part of the
psr/path-matching package.

3. Example Implementation
-------------------------

The example implementation MUST NOT be regarded as part of the specification; it is
an example only. Path matchers MAY contain additional features and MAY differ in how
they are implemented. As long as a path matcher adheres to the rules set forth in
the specification it MUST be considered compatible with this PSR.

```php
<?php

/**
 * An example implementation of the above specification that finds a match
 * for a logical path when given a mapping of paths to base paths and a
 * separator character.
 *
 * Note that this is only an example, and is not a specification in itself.
 */
function match_path($path, array $path_mappings, $separator)
{
    // remember the length of the path
    $path_length = strlen($path);
    
    // first see if the complete path is mapped
    $path_prefix = $path;

    // path relative to the path prefix
    $relative_path = '';

    // the reverse offset of the separator dividing the path
    // prefix from the relative path
    $cursor = -1;
    
    while (true) {
        // are there any base paths for this path prefix?
        if (isset($path_mappings[$path_prefix])) {
            // look through base paths for this path prefix
            foreach ((array) $path_mappings[$path_prefix] as $base_path) {
                // separators must be replaced by directory separators
                $relative_path = strtr($relative_path, $separator, DIRECTORY_SEPARATOR);

                // create a potential match from the base path and the
                // relative path
                $potential_match = $base_path . $relative_path;
                
                // can we read the file from the file system?
                if (is_readable($potential_match)) {
                    // yes, we have a match
                    return $potential_match;
                }
            }
        }
        
        // once the cursor tested the first character, the
        // algorithm terminates
        if ($path_prefix === $separator) {
            return;
        }
        
        // place the cursor on the next separator to the left
        $cursor = strrpos($path, $separator, $cursor - 1) - $path_length;
        
        // the relative path is the part right of and including
        // the cursor, e.g. "/Parser.php"
        $relative_path = substr($path, $cursor);
        
        // the path prefix is the part left of and including
        // the cursor, e.g. "/Acme/Demo/"
        $path_prefix = substr($path, 0, $cursor + 1);
    }
}
```
