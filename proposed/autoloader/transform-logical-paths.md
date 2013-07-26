PSR-T: Transformation Of Logical Paths To File System Paths
===========================================================

This document describes an algorithm to transform a logical resource path to a
file system path. Among other things, the algorithm allows transformation of
class paths and other logical resource paths to file system paths.

The key words "MUST", "MUST NOT", "REQUIRED", "SHALL", "SHALL NOT", "SHOULD",
"SHOULD NOT", "RECOMMENDED", "MAY", and "OPTIONAL" in this document are to be
interpreted as described in [RFC 2119](http://tools.ietf.org/html/rfc2119).


1. Definitions
--------------

**Logical Separator**: A single character to delimit _logical segments_; for
example, a slash, backslash, colon, etc.

**Logical Segment**: A string delimited by _logical separators_.

**Logical Path**: A string composed of _logical segments_ and _logical
separators_. A _logical path_ MUST begin with a _logical separator_, and it
MAY end with a _logical separator_. A _logical path_ that ends in a _logical
separator_ represents the equivalent of a directory, and a _logical path_ that
does not end in a _logical separator_ represents the equivalent of a file.

**Logical Prefix**: Any series of contiguous _logical segments_ and _logical
separators_ at the beginning of a _logical path_ ending with a _logical
separator_. For example, given a _logical separator_ of `:` and a _logical
path_ of `:Foo:Bar:Baz`, the valid _logical prefixes_ are `:`, `:Foo:`,
`:Foo:Bar:`, and `:Foo:Bar:Baz:`. Note that the _logical path_ and the
_logical prefix_ MAY be identical when the _logical path_ ends in a _logical
separator_. The _logical prefix_ represents the equivalent of a directory.

**Logical Suffix**: Given a _logical path_ and a _logical prefix_,
the _logical suffix_ is the remainder of the _logical path_ after the
_logical prefix_. For example, given a _logical separator_ of `:`, a
_logical path_ of `:Foo:Bar:Baz:Qux`, and a _logical prefix_ of
`:Foo:Bar:`, then `Baz:Qux` is the _logical suffix_.

**Directory Prefix**: A directory in the file system associated with a
_logical prefix_. The _directory prefix_ MUST end in a directory separator;
it MAY begin with a directory separator.

**Transformed Path**: The file system path resulting from the transformation
algorithm specified below.


2. Specification
----------------

Given a logical path, a logical prefix, a logical separator, and a directory
prefix, implementations MUST transform the logical path into a path that MAY
exist in the file system.

- **Normalize.** The implementation MUST ...

    - normalize the logical path so that it begins with a logical separator,

    - normalize the logical prefix so that it begins and ends with a logical
      separator, and

    - normalize the directory prefix so that it ends with directory separator.

- **Validate.** If the logical prefix is not valid for the logical path, the
  implementation MUST abort the transformation and MAY return `false` or
  `null`.

- **Transform.** The implementation MUST ...

    - replace the logical prefix in the logical path with the directory
      prefix, and

    - replace logical separators in the logical suffix with directory
      separators.

The result is the transformed path.


3. Example Implementation
-------------------------

The example implementation MUST NOT be regarded as part of the specification;
it is an example only. Implementations MAY contain additional features and MAY
differ in how they are implemented.

```php
<?php
/**
 * Example implementation.
 * 
 * Note that this is only an example, and is not a specification in itself.
 * 
 * @param string $logical_path The logical path to transform.
 * @param string $logical_prefix The logical prefix associated with $dir_prefix.
 * @param string $logical_sep The logical separator in the logical path.
 * @param string $dir_prefix The directory prefix for the transformation.
 * @return string The logical path transformed into a file system path.
 */
function transform(
    $logical_path,
    $logical_prefix,
    $logical_sep,
    $dir_prefix
) {
    // normalize logical path: leading logical separator
    $logical_path = $logical_sep . ltrim($logical_path, $logical_sep);
    
    // normalize logical prefix: leading and trailing logical separators.
    // do this in two steps so that we don't destroy a single separator.
    $logical_prefix = $logical_sep . ltrim($logical_prefix, $logical_sep);
    $logical_prefix = rtrim($logical_prefix, $logical_sep) . $logical_sep;
    
    // normalize directory prefix: trailing directory separator
    $dir_prefix = rtrim($dir_prefix, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
    
    // make sure prefixes match exactly
    $len = strlen($logical_prefix);
    if (substr($logical_path, 0, $len) !== $logical_prefix) {
        return false;
    }
    
    // extract the logical suffix
    $logical_suffix = substr($logical_path, $len);
    
    // complete the transformation
    return $dir_prefix
         . str_replace($logical_sep, DIRECTORY_SEPARATOR, $logical_suffix);
}
?>
```
