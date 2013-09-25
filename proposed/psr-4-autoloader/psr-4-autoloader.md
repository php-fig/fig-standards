PSR-4: Autoloader
=================

The key words "MUST", "MUST NOT", "REQUIRED", "SHALL", "SHALL NOT", "SHOULD",
"SHOULD NOT", "RECOMMENDED", "MAY", and "OPTIONAL" in this document are to be
interpreted as described in [RFC 2119](http://tools.ietf.org/html/rfc2119).

[Meta Document](psr-4-autoloader-meta.md)

1. Overview
-----------

This PSR specifies the rules for an interoperable PHP autoloader that maps
namespaces to file system paths, and that can co-exist with any other SPL
registered autoloader.


2. Definitions
--------------

- **class**: The term _class_ refers to PHP classes, interfaces, and traits.

- **fully qualified class name**: The full namespace and class name, with the
  leading namespace separator. (This is per the
  [Name Resolution Rules](http://php.net/manual/en/language.namespaces.rules.php)
  from the PHP manual.)

- **namespace name**: Given a fully qualified class name of
  `\Foo\Bar\Baz\Qux`, the _namespace names_ are `Foo`, `Bar`, and `Baz`.

- **namespace prefix**: Given a fully qualified class name of
  `\Foo\Bar\Baz\Qux`, the _namespace prefix_ may be `\Foo\`, `\Foo\Bar\`, or
  `\Foo\Bar\Baz\`.

- **relative class name**: The parts of the fully qualified class name that
  appear after the namespace prefix. Given a fully qualified class name of
  `\Foo\Bar\Baz\Qux` and a namespace prefix of `\Foo\Bar\`, the _relative
  class name_ is `Baz\Qux`.

- **base directory**: The directory path in the file system where the files
  for relative class names have their root. Given a namespace prefix of
  `\Foo\Bar\`, the _base directory_ could be `/path/to/packages/foo-bar/src`.

- **mapped file name**: The path in the file system resulting from the
  transformation of a fully qualified class name. Given a fully qualified
  class name of `\Foo\Bar\Baz\Qux`, a namespace prefix of `\Foo\Bar\`, and a
  base directory of `/path/to/packages/foo-bar/src`, the transformation
  rules in the specification will result in a _mapped file name_ of
  `/path/to/packages/foo-bar/src/Baz/Qux.php`.


3. Specification
----------------

### 3.1. General

The fully qualified class name MUST begin with a namespace name, which MAY be
followed by one or more additional namespace names, and MUST end in a class
name.

At least one namespace prefix of the fully qualified class name MUST
correspond to a base directory.

A namespace prefix MAY correspond to more than one base directory.

### 3.2. Registered Autoloaders

The registered autoloader MUST transform the fully qualified class name
using the rules in section 3.3. The result MUST be suffixed with `.php` to
generate a mapped file name.

If the mapped file name exists in the file system, the registered autoloader
MUST include or require it.

The registered autoloader MUST NOT throw exceptions, MUST NOT raise errors of
any level, and SHOULD NOT return a value.

### 3.3. Transformation

Given a fully qualified class name, a namespace prefix, and a base directory
that corresponds with that namespace prefix ...

- The fully qualified class name MUST be normalized so that any leading
  namespace separator is removed. (PHP versions 5.3.3 and later do this
  automatically.)

- The namespace prefix MUST be normalized so that any leading namespace
  separator is removed, and so that it ends with a namespace separator.

- The base directory MUST be normalized so that it ends with directory
  separator.

- The namespace prefix portion of the fully qualified class name MUST be
  replaced with the corresponding base directory.

- Namespace separators in the relative class name portion of the fully
  qualified class name MUST be replaced with directory separators.


4. Implementations
------------------

For example implementations, please see [the relevant wiki page][]. Example
implementations MUST NOT be regarded as part of the specification. They are
examples only, and MAY change at any time.

[the relevant wiki page]: https://github.com/php-fig/fig-standards/wiki/PSR-4-Example-Implementations

