PSR-4: Autoloader
=================

The key words "MUST", "MUST NOT", "REQUIRED", "SHALL", "SHALL NOT", "SHOULD",
"SHOULD NOT", "RECOMMENDED", "MAY", and "OPTIONAL" in this document are to be
interpreted as described in [RFC 2119](http://tools.ietf.org/html/rfc2119).


1. Overview
-----------

This PSR specifies the rules for an interoperable PHP autoloader that maps
namespaces to file system paths, and that can co-exist with any other SPL
registered autoloader.


2. Definitions
--------------

- **class**: The term _class_ refers to PHP classes, interfaces, and traits.

- **fully qualified class name**: The full namespace and class name. The
  _fully qualified class name_ MUST NOT include a leading namespace separator.

- **namespace name**: Given a _fully qualified class name_ of
  `Acme\Log\Writer\FileWriter`, the _namespace names_ are `Acme`, `Log`, and
  `Writer`. A _namespace name_ MUST NOT include a leading or trailing
  namespace separator.
  
- **namespace prefix**: Given a _fully qualified class name_ of
  `Acme\Log\Writer\FileWriter`, the _namespace prefix_ may be `Acme\`,
  `Acme\Log\`, or `Acme\Log\Writer\`. A _namespace prefix_ MUST NOT include
  a leading namespace separator, but MUST include a trailing namespace
  separator.

- **relative class name**: The parts of the _fully qualified class name_ that
  appear after the _namespace prefix_. Given a _fully qualified class name_ of
  `Acme\Log\Writer\FileWriter` and a _namespace prefix_ of `Acme\Log\`, the
  _relative class name_ is `Writer\FileWriter`. A _relative class name_ MUST
  NOT include a leading namespace separator.

- **base directory**: A directory path in the file system where files for
  _relative class names_ have their root. Given a namespace prefix of
  `Acme\Log\`, a _base directory_ could be `/path/to/packages/acme-log/src`.
  A _base directory_ MUST include a trailing directory separator.

- **mapped file name**: The path in the file system resulting from the
  transformation of a _fully qualified class name_. Given a _fully qualified
  class name_ of `Acme\Log\Writer\FileWriter`, a _namespace prefix_ of
  `Acme\Log\`, and a _base directory_ of `/path/to/packages/acme-log/src`,
  the _mapped file name_ MUST be
  `/path/to/packages/acme-log/src/Writer/FileWriter.php`.

3. Specification
----------------

### 3.1. General

The _fully qualified class name_ MUST begin with a _namespace name_, which MAY
be followed by one or more additional _namespace names_, and MUST end in a
class name.

At least one _namespace prefix_ of the _fully qualified class name_ MUST
correspond to a _base directory_.

A _namespace prefix_ MAY correspond to more than one _base directory_.

### 3.2. Registered Autoloaders

1. Registered autoloaders MUST transform the _fully qualified class name_ into a
_mapped file name_ as follows:

    a. The _namespace prefix_ portion of the _fully qualified class name_ MUST be
    replaced with the corresponding _base directory_.

    b. Namespace separators in the _relative class name_ portion of the
    _fully qualified class name_ MUST be replaced with directory separators
    for the respective operating system.

    c. The result MUST be suffixed with `.php`.

2. If the _mapped file name_ exists in the file system, the registered
autoloader MUST include or require it.

3. The registered autoloader MUST NOT throw exceptions, MUST NOT raise errors
of any level, and SHOULD NOT return a value.


4. Implementations
------------------

For example implementations, please see [the relevant wiki page][examples].
Example implementations MUST NOT be regarded as part of the specification. 
They are examples only, and MAY change at any time.

[examples]: https://github.com/php-fig/fig-standards/wiki/PSR-4-Example-Implementations
