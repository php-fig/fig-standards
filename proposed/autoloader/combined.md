PSR-X: Autoloader
=================

The key words "MUST", "MUST NOT", "REQUIRED", "SHALL", "SHALL NOT", "SHOULD",
"SHOULD NOT", "RECOMMENDED", "MAY", and "OPTIONAL" in this document are to be
interpreted as described in [RFC 2119](http://tools.ietf.org/html/rfc2119).


1. Overview
-----------

This PSR specifies the rules for an interoperable PHP autoloader which maps
namespaces to file system paths, and that can co-exist with any other SPL
registered autoloader.


2. Definitions
--------------

These definitions are presented in addition to the terms defined in PSR-T.

- **class**: The term _class_ refers to PHP classes, interfaces, and traits.

- **fully qualified class name**: The full namespace and class name, with the
  leading namespace separator. (This is per the
  [Name Resolution Rules](http://php.net/manual/en/language.namespaces.rules.php)
  from the PHP manual.)

- **namespace name**: Given a _fully qualified class name_ of
  `\Foo\Bar\Baz\Qux`, the _namespace names_ are `Foo`, `Bar`, and `Baz`.
  
- **namespace prefix**: Given a _fully qualified class name_ of
  `\Foo\Bar\Baz\Qux`, the _namespace prefix_ may be `\Foo\`, `\Foo\Bar\`, or
  `\Foo\Bar\Baz\`.

- **relative class name**: The parts of the _fully qualified class name_ that
  appear after the _namespace prefix_. Given a _fully qualified class name_ of
  `\Foo\Bar\Baz\Qux` and a _namespace prefix_ of `\Foo\Bar\`, the _relative
  class name_ is `Baz\Qux`.

- **base directory**: The directory path in the file system where the files
  for _relative class names_ have their root. Given a namespace prefix of 
  `\Foo\Bar\`, the _base directory_ could be `/path/to/packages/foo-bar/src`.

- **mapped file name**: The path in the file system resulting from the
  transformation of a _fully qualified class name_. Given a _fully qualified
  class name_ of `\Foo\Bar\Baz\Qux`, a namespace prefix of `\Foo\Bar\`, and a
  _base directory_ of `/path/to/packages/foo-bar/src`, the transformation
  rules in the specification will result in a _mapped file name_ of
  `/path/to/packages/foo-bar/src/Baz/Qux.php`.


3. Specification
----------------

- The fully qualified class name MUST begin with a top-level namespace name,
  which MUST be followed by zero or more sub-namespace names, and MUST end in
  a class name.

- A namespace prefix of the fully qualified class name MUST be mapped to a
  base directory; that namespace prefix MAY be mapped to more than one base
  directory.

- The fully-qualified class name MUST be transformed into a mapped file name
  by:

    - replacing the namespace prefix in the fully-qualified class name with
      the associated base directory;

    - replacing namespace separators in the relative class name with directory
      separators; and,
      
    - suffixing the result with `.php`.
    
- If the mapped file name exists in the file system, the registered autoloader
  MUST include or require it.

- The registered autoloader MUST NOT throw exceptions, MUST NOT raise errors
  of any level, and SHOULD NOT return a value.


4. Implementations
------------------

Implementations MAY contain additional features and MAY differ in how they are
implemented.

For example implemenations, see _CombinedTest.php_. Example implementations
MUST NOT be regarded as part of the specification; they are examples only.
