PSR-M: Matching Logical Paths To File Paths
===========================================

This document describes an algorithm to match a logical resource path to one
or more file paths in a file system.

The key words "MUST", "MUST NOT", "REQUIRED", "SHALL", "SHALL NOT", "SHOULD",
"SHOULD NOT", "RECOMMENDED", "MAY", and "OPTIONAL" in this document are to be
interpreted as described in [RFC 2119](http://tools.ietf.org/html/rfc2119).


1. Definitions
--------------

(These are in addition to the terms defined in PSR-T.)

**Prefix Mapping**: One or more _directory path prefixes_ associated with a
_logical path prefix_.


2. Specification
----------------

Given a fully qualified logical path, a collection of prefix mappings, and a
logical separator, the implementation MUST attempt to find one or more
readable files in the file system that match the transformation result.

- The implementation SHOULD attempt all possible transformations of the fully
  qualified logical path into a file name using the collection of prefix
  mappings. When doing so, the implementation:
  
    - MUST try each possible logical path prefix in the fully qualified
      logical path, in order from deepest to shallowest; and
    
    - MUST try each directory path prefix in the prefix mapping for the
      logical path prefix, in order from first to last.

- For each file name resulting from transformation, the implementation MUST
  determine if the file name is readable from the file system.
  
    - The implementation MAY modify the file name.

    - If the file name is readable, the implementation MAY return the matched
      file name immediately, thereby exiting this algorithm; otherwise, the
      implementation MUST retain the matched file name for later return.
    
    - If the file name is not readable, the implementation MUST continue to
      transform the fully qualified logical path into the next possible file
      name.

- After trying all possible transformations of the fully qualified logical
  path using the collection of prefix mappings, the implementation MUST return
  the collection of all matched file names, in the order they were collected.

- If the implementation did not match any file names, it MUST return an empty
  value, such as a boolean false or an array with no elements.


3. Example Implementation
-------------------------

The example implementation MUST NOT be regarded as part of the specification;
it is an example only. Implementations MAY contain additional features and MAY
differ in how they are implemented.

```php
<?php
/**
 * Example implementation to return the first matched file.
 * 
 * Note that this is only an example, and is not a specification in itself.
 * 
 * @param string $logical_path The logical path to match against.
 * @param array $prefixes An array of key-value pairs where the key is a
 * logical path prefix and the value is an array of directory path prefixes
 * associated with that logical path prefix.
 * @param string $logical_sep The logical separator.
 */
function match($logical_path, array $prefixes, $logical_sep)
{
    // go through all possible logical prefixes in the logical path; work from
    // deepest to shallowest; remove the leading logical separator from the
    // logical path so that explode() does not give an empty starting segment.
    $segments = explode($logical_sep, ltrim($logical_path, $logical_sep));
    while ($segments) {
        
        // create a logical prefix by removing the last segment and
        // concatenating the remainder
        array_pop($segments);
        $logical_prefix = $logical_sep . implode($segments, $logical_sep);
        
        // is there a mapping for this logical prefix?
        if (isset($prefixes[$logical_prefix]) == false) {
            // no, try a shallower prefix
            continue;
        }
        
        // look through the directory prefixes for the logical prefix
        foreach ($prefixes[$logical_prefix] as $dir_prefixes) {
            foreach ((array) $dir_prefixes as $dir_prefix) {
                $file = transform(
                    $logical_path,
                    $logical_prefix,
                    $logical_sep,
                    $dir_prefix
                );
                if (is_readable($file)) {
                    return $file;
                }
            }
        }
    }
    
    // did not find a matching file
    return false;
}


/**
 * Example implementation to return all matched files.
 * 
 * Note that this is only an example, and is not a specification in itself.
 * 
 * @param string $logical_path The logical path to match against.
 * @param array $prefixes An array of key-value pairs where the key is a
 * logical path prefix and the value is an array of directory path prefixes
 * associated with that logical path prefix.
 * @param string $logical_sep The logical separator.
 */
function match($logical_path, array $prefixes, $logical_sep)
{
    // all matched files
    $matches = [];
    
    // go through all possible logical prefixes in the logical path; work from
    // deepest to shallowest; remove the leading logical separator from the
    // logical path so that explode() does not give an empty starting segment.
    $segments = explode($logical_sep, ltrim($logical_path, $logical_sep));
    while ($segments) {
        
        // create a logical prefix by removing the last segment and
        // concatenating the remainder
        array_pop($segments);
        $logical_prefix = $logical_sep . implode($segments, $logical_sep);
        
        // is there a mapping for this logical prefix?
        if (isset($prefixes[$logical_prefix]) == false) {
            // no, try a shallower prefix
            continue;
        }
        
        // look through the directory prefixes for the logical prefix
        foreach ($prefixes[$logical_prefix] as $dir_prefixes) {
            foreach ((array) $dir_prefixes as $dir_prefix) {
                $file = transform(
                    $logical_path,
                    $logical_prefix,
                    $logical_sep,
                    $dir_prefix
                );
                if (is_readable($file)) {
                    $matches[] = $file;
                }
            }
        }
    }
    
    // return all matched files, if any
    return $matches;
}
```
