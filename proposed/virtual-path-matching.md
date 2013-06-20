> This proposal is annotated with comments in quote blocks like this one. These
> annotations should help to clarify why certain passages exist and what other
> alternatives exist or have been tried.
>
> The final PSR will not contain these annotations, but a copy could be preserved
> for documentation purposes.
>
> **TL;DR**
>
> Finds actual paths on a file system for virtual paths, such as FQCNs
> ("\Acme\Demo\Parser") or URI paths ("/acme/demo-package/show.html.php"),
> using a mapping of virtual paths to base directories. The separator
> character can be chosen by the implementation.
>
> Example:
>
> ```php
> $sep = '/';
> $mapping = array(
>    '\Acme\Blog\' => 'src/blog',
>    '\Acme\Demo\Parser.php' => 'src/Parser.php',
> );
>
> echo match_path('\Acme\Blog\ShowController.php', $mapping, $sep);
> // => "src/blog/ShowController.php"
>
> echo match_path('\Acme\Demo\Parser.php', $mapping, $sep);
> // => "src/Parser.php"
> ```
>
> The algorithm does not care about file suffixes or the distinction between
> files and directories. Restrictions in this regard can be made by PSRs
> using this algorithm.
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
> - If the matching file name exists in the file system, the registered
>   autoloader MUST include or require it.
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
>   used in the PSR-X autoloader.

Virtual Path Matching
=====================

This document describes an algorithm that finds the real path(s) for a virtual
path when given a mapping of path prefixes to directories.

The main goal is to provide a foundation for future PSRs based on this
algorithm, such as an autoloader PSR, a resource location PSR and so on.

The key words "MUST", "MUST NOT", "REQUIRED", "SHALL", "SHALL NOT", "SHOULD",
"SHOULD NOT", "RECOMMENDED", "MAY", and "OPTIONAL" in this document are to be
interpreted as described in [RFC 2119](http://tools.ietf.org/html/rfc2119).

1. Definitions
--------------

**Path Matcher**: A program implementing the path matching algorithm described
in section 2.

> This should neither be fixed to PHP code, nor C code, nor a method, nor a
> function. "Program" is a generic term that matches all of these concepts.

**Separator**: A single character chosen by the path matcher, e.g. a slash ("/").

> Allows to use this algorithm for both autoloading (separator: "\") and
> resource location (separator: "/").

**Path**: A sequence of zero or more path segments, separated by slashes and
starting with a slash. `/`, `/A`, `/A/` and `/A/B` are valid paths.

**Virtual Path**: A path that does not necessarily exist on the file system.

> E.g. a namespace (\Acme\Demo\Parser) or a URI path (/acme/demo-package/config)

**Path Prefix**: A sequence of zero or more path segments at the beginning of a
path, divided by separators. A path prefix MUST start and end with a separator
character. Given the separator "/", then `/`, `/A/` and `/A/B/` are valid path
prefixes.

**Relative Path**: A sequence of one or more path segments, divided by
separators and relative to a path prefix. A relative path MUST NOT start or
end with a separator character. Given the separator "/", a path `/A/B/C/D`
and a path prefix `/A/B/`, then `C/D` is the relative path.

**Path Mapping**: A set of paths, each of which is assigned to one or more
existing directories on the local file system (the *base directories*).
The base directories MUST be provided either as absolute paths or as URIs with
one of the following schemes:

* file://
* phar://
* zlib://
* zip://
* bzip2://

**Mapped Path (Prefix)**: A path (prefix) contained in a given path mapping.

2. Path Matching Algorithm
--------------------------

Compliant path matchers MUST implement this algorithm to find matches for
virtual paths.

Given the separator "/", a path `/A/B/C/D` and a path mapping which assigns
`/A/B/C/D` to one or more base directories, then every base directory is a
*potential match*.

> The full path itself can be mapped. Needed in the resource location PSR to
> look up the directories that a namespace is mapped to.

Given the separator "/", a path `/A/B/C/D`, the path prefix `/A/B/`,
the relative path `C/D` and a path mapping which assigns `/A/B/` to the base
directory `/src`, then a *potential match* is generated by concatenating the
base directory, a slash ("/") and the relative path: `/src/C/D`.

Separators in potential matches MUST be replaced by slashes.

> Allows the use of other separators than slashes, e.g. backslashes ("\").

A potential match `/src/C/D` is *evaluated* by checking whether it exists on
the local file system. If it does, it is called a *match*.

> Matches must exist.

If both a full path and any of its prefixes are mapped, potential matches
for the full path MUST be evaluated before those for the prefixes. If a mapped
path contains multiple mapped prefixes, potential matches for longer prefixes
MUST be evaluated before those for shorter prefixes. 

> Define order of evaluation..

If a prefix is mapped to multiple base directories, the potential matches MUST
be evaluated in the order of the base directories.

> When multiple implementations of this algorithm (e.g. PSR-X and PSR-R)
> receive the same mapping, they should behave identically.

A path matcher MAY choose to abort this algorithm once a match has been found,
or continue in order to generate all matches.

> Find first vs. find all matches.

A path matcher MAY not find a match for a virtual path. The result in this
case is undefined.

> Exception, returning null etc. can be chosen by the implementation.

3. EBNF
-------

TODO

4. Example Implementations
--------------------------

TODO
