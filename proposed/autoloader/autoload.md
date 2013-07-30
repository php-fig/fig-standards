PSR-X: Autoloader
=================

The key words "MUST", "MUST NOT", "REQUIRED", "SHALL", "SHALL NOT", "SHOULD",
"SHOULD NOT", "RECOMMENDED", "MAY", and "OPTIONAL" in this document are to be
interpreted as described in [RFC 2119](http://tools.ietf.org/html/rfc2119).


1. Overview
-----------

This PSR specifies the rules for an interoperable PHP autoloader which maps
namespaces to file system paths, and that can co-exist with any other SPL
registered autoloader. It is an application of the PSR-T rules for
transforming logical paths to file paths.


2. Definitions
--------------

These definitions are presented in addition to the terms defined in PSR-T.

- **class**: The term _class_ refers to PHP classes, interfaces, and traits.

- **fully qualified class name**: The full namespace and class name, with the
  leading namespace separator. (This is per the
  [Name Resolution Rules](http://php.net/manual/en/language.namespaces.rules.php)
  from the PHP manual.) This is the equivalent of a _logical path_ as defined
  in PSR-T.

- **namespace names**: Given a _fully qualified class name_ of
  `\Foo\Bar\Baz\Qux`, the _namespace names_ are `Foo`, `Bar`, and `Baz`.
  These are the equivalent of _logical segments_ as defined in PSR-T.
  
- **namespace prefix**: Given a _fully qualified class name_ of
  `\Foo\Bar\Baz\Qux`, the _namespace prefix_ may be `\Foo\`, `\Foo\Bar\`, or
  `\Foo\Bar\Baz\`. This is the equivalent of a _logical prefix_ as defined in 
  PSR-T.

- **relative class name**: The parts of the _fully qualified class name_ that
  appear after the _namespace prefix_. Given a _fully qualified class name_ of
  `\Foo\Bar\Baz\Qux` and a _namespace prefix_ of `\Foo\Bar\`, the _relative
  class name_ is `Baz\Qux`. This is the equivalent of a _logical suffix_ as
  defined in PSR-T.

- **base directory**: The directory path in the file system where the files for
  _relative class names_ have their root. Given a namespace prefix of 
  `\Foo\Bar\`, the _base directory_ could be `/path/to/packages/foo-bar/src`.

- **mapped file name**: The path in the file system resulting from the
  transformation of a _fully qualified class name_. Given a _fully qualified
  class name_ of `\Foo\Bar\Baz\Qux`, a namespace prefix of `\Foo\Bar\`, and a
  _base directory_ of `/path/to/packages/foo-bar/src`, the transformation
  rules in the specification will result in a _mapped file name_ of
  `/path/to/packages/foo-bar/src/Baz/Qux.php`. This is the equivalent of a
  _transformed path_ as defined in PSR-T.


3. Specification
----------------

- A fully qualified class name MUST begin with a top-level namespace name,
  which MUST be followed by zero or more sub-namespace names, and MUST end in
  a class name.  This means that a PSR-X compliant class name MUST consist of
  at least one namespace name and a class name.

- The namespace prefix of a fully qualified class name MUST be mapped to a
  base directory; that namespace prefix MAY be mapped to more than one base
  directory.

- The class name MUST be transformed into a file path using PSR-T. The class
  name MUST be used as the logical path, a namespace prefix MUST be used as
  the logical prefix, the logical separator MUST be a backslash, the related
  base directory for the namespace prefix MUST be used as the directory
  prefix. The transformed path MUST be suffixed with `.php`, resulting in a
  _mapped file name_.

- If the _mapped file name_ exists in the file system, the registered
  autoloader MUST include or require it.

- The registered autoloader callback MUST NOT throw exceptions, MUST NOT
  raise errors of any level, and SHOULD NOT return a value.


4. Implementations
------------------

Implementations MAY contain additional features and MAY differ in how they are
implemented.

For example implemenations, see [AutoloadTest.php][]. Example implementations
MUST NOT be regarded as part of the specification; they are examples only, and
may change at any time.
