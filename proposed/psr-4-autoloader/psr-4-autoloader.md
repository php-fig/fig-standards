# PSR-4: Autoloader

The key words "MUST", "MUST NOT", "REQUIRED", "SHALL", "SHALL NOT", "SHOULD",
"SHOULD NOT", "RECOMMENDED", "MAY", and "OPTIONAL" in this document are to be
interpreted as described in [RFC 2119](http://tools.ietf.org/html/rfc2119).


## 1. Overview

This PSR describes a technique to [autoload][] classes from specified resource
paths. It is fully interoperable, and can be used in addition to any other
autoloading technique, including [PSR-0][]. This PSR also describes how to
name and structure classes to be autoloaded using the described technique.

[autoload]: http://php.net/autoload
[PSR-0]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-0.md


## 2. Definitions

- **class**: The term _class_ refers to PHP classes, interfaces, traits, and
  similar future resource definitions.

- **namespace**: A PHP namespace, as is syntactically valid after the
  [PHP `namespace` keyword](http://www.php.net/manual/en/language.namespaces.definition.php).
  `\` by itself is not an acceptable _namespace_ within this PSR.

- **namespace separator**: The PHP namespace separator symbol `\` (backslash).

- **fully qualified class name**: A full namespace and class name, such as
  `\Acme\Log\Writer\FileWriter`. As defined by
  [PHP's name resolution rules](http://php.net/manual/en/language.namespaces.rules.php),
  the _fully qualified class name_ MUST include the leading namespace
  separator.

- **autoloadable class name**: Any class intended for autoloading. (Classes
  not intended for autoloading are not covered by this term.) The
  _autoloadable class name_ is the same as the _fully qualified class name_
  but it MUST NOT include the leading namespace separator. Given a _fully
  qualified class name_ of `\Acme\Log\Writer\FileWriter`, the _autoloadable
  class name_ is `Acme\Log\Writer\FileWriter`. Typically, this is the
  value sent to the [__autoload()][] function and to callbacks registered with
  [spl_autoload_register()][].
  
- **namespace part**: The individual non-terminating parts of an _autoloadable
  class name_. Given an _autoloadable class name_ of
  `Acme\Log\Writer\FileWriter`, the _namespace parts_ are `Acme`, `Log`, and
  `Writer`. A _namespace part_ MUST NOT include a leading or trailing
  namespace separator.

- **class part**: The individual terminating part of an _autoloadable class
  name_. Given an _autoloadable class name_ of `Acme\Log\Writer\FileWriter`,
  the _class part_ is `FileWriter`. A _class part_ MUST NOT include a leading
  namespace separator.

- **namespace prefix**: One or more contiguous leading _namespace parts_ with
  namespace separators. Given an _autoloadable class name_ of
  `Acme\Log\Writer\FileWriter`, a _namespace prefix_ may be `Acme\`,
  `Acme\Log\`, or `Acme\Log\Writer\`. A _namespace prefix_ MUST NOT include a
  leading namespace separator, but MUST include a trailing namespace
  separator.

- **relative class name**: The parts of the _autoloadable class name_ that
  appear after the _namespace prefix_. Given an _autoloadable class name_ of
  `Acme\Log\Writer\FileWriter` and a _namespace prefix_ of `Acme\Log\`, the
  _relative class name_ is `Writer\FileWriter`. A _relative class name_ MUST
  NOT include a leading namespace separator.

- **resource**: A class definition, typically a file in a file system.

- **scheme**: A resource storage-and-retrieval mechanism, typically a file
  system.

- **resource base**: A base path to _resources_ for a particular _namespace
  prefix_. Given a file system _scheme_ and a _namespace prefix_ of
  `Acme\Log\`, a _resource base_ MAY be `/path/to/acme-log/src/`. A _resource
  base_ MUST include a _scheme_-appropriate trailing separator, and MAY
  include a _scheme_-appropriate leading separator. In a file system _scheme_,
  that separator MUST be the constant `DIRECTORY_SEPARATOR`.

- **resource path**: A path in the _scheme_ representing a _resource_ defining
  an _autoloadable class name_. Given an _autoloadable class name_ of
  `Acme\Log\Writer\FileWriter`, a _namespace prefix_ of `Acme\Log\`, a
  UNIX-like file system _scheme_, a _resource base_ of
  `/path/to/acme-log/src`, and the specification described below, the
  _resource path_ MUST be `/path/to/acme-log/src/Writer/FileWriter.php`. The
  _resource path_ MAY or MAY NOT actually exist in the _scheme_.

- **conforming autoloader**: PHP autoloader code that implements the technique
  described in the specification below.

[__autoload()]: http://php.net/manual/en/function.autoload.php
[spl_autoload_register()]: http://php.net/manual/en/function.spl-autoload-register.php


## 3. Specification

### 3.1. Preamble

For a _conforming autoloader_ to be able to transform an _autoloadable class
name_ into a _resource path_, this specification describes a technique that
MUST be applied or taken into account. When the technique is applied, the
_conforming autoloader_ can autoload an _autoloadable class name_ from an
existing _resource path_.

Aside from technical considerations, this specification also imposes
requirements on developers who want their classes to be autoloadable by a
_conforming autoloader_. Developers who wish to comply with this specification
MUST structure their classes using these same principles.

### 3.2. Technique

1. Each _autoloadable class name_ MUST begin with a _namespace part_, which
MAY be followed by one or more additional _namespace parts_, and MUST end in a
_class part_.

    a. The beginning _namespace part_ of the _autoloadable class name_,
    sometimes called a "vendor name", MUST be unique to the developer or
    project. This is to prevent conflicts between different libraries,
    components, modules, etc.
    
    b. It is RECOMMENDED (but not required) that the _autoloadable class name_
    include a second _namespace part_, sometimes called a "package name", to
    identify its place within the "vendor name".

2. At least one _namespace prefix_ of each _autoloadable class name_ MUST
correspond to a _resource base_.

    a. A _namespace prefix_ MAY correspond to more than one _resource base_.
    (The order in which a _conforming autoloader_ processes more than one
    corresponding _resource base_ is undefined.)

    b. To prevent conflicts, different _namespace prefixes_ SHOULD NOT
    correspond to the same _resource base_.

3. A _conforming autoloader_ MUST process an _autoloadable class name_, its
_namespace prefixes_, and their corresponding _resource bases_ as follows:

    a. The _namespace prefix_ portion of the _autoloadable class name_ MUST
    be replaced with the corresponding _resource base_.

    b. Each _namespace separator_ in the _relative class name_ portion of the
    _autoloadable class name_ MUST be replaced with a _scheme_-appropriate
    separator. In a file system _scheme_, that separator MUST be the
    `DIRECTORY_SEPARATOR` constant.

    c. The result MUST be suffixed with `.php` to create a _resource path_.

    d. If the _resource path_ exists in the _scheme_, it MUST be included,
    required, or otherwise loaded so that it becomes available.

    e. Because a _namespace prefix_ MAY correspond to more than one _resource
    base_, a _conforming autoloader_ SHOULD process each corresponding
    _resource base_ for that _namespace prefix_ until it finds an existing
    _resource path_ for the _autoloadable class name_. (The behavior for a
    _conforming autoloader_ when it cannot find a _resource path_ for an
    _autoloadable class name_ is undefined.)

    f. The order in which a _conforming autoloader_ attempts to process
    multiple _resource bases_ corresponding to a _namespace prefix_ is not
    within the scope of this specification. Developers should be aware that
    different approaches MAY be used and SHOULD refer to the documentation of
    the _conforming autoloader_ for more information.

4. A _conforming autoloader_ MUST NOT interfere with other autoloaders: it
MUST NOT throw exceptions, MUST NOT raise errors of any level, and SHOULD NOT
return a value.

5. Developers who want their classes to be autoloadable by a _conforming
autoloader_ MUST specify how their _namespace prefixes_ correspond to
_resource bases_. The approach is left to the developer. It may be via
narrative documentation, meta-files, PHP source code, project-specific
conventions, or some other approach.


### 3.3. Example

The following example MUST NOT be regarded as part of the specification. It is
for example purposes only.

Given a UNIX-like file system _scheme_, a _fully qualified class name_ of
`\Acme\Log\Writer\FileWriter`, a _namespace prefix_ of `Acme\Log\`, and a
_resource base_ of `/path/to/acme-log/src/`, the above specification will
result in the following actions by a _conforming autoloader_:

1. `\Acme\Log\Writer\FileWriter` becomes `Acme\Log\Writer\FileWriter`.

2. The _namespace prefix_ is replaced with the _resource base_. That is,
`Acme\Log\Writer\FileWriter` is transformed into
`/path/to/acme-log/src/Writer\FileWriter`.

3. The _namespace separators_ in the _relative class name_ are replaced with
_scheme_-appropriate separators. That is,
`/path/to/acme-log/src/Writer\FileWriter` is tranformed into
`/path/to/acme-log/src/Writer/FileWriter`.

4. The result is appended with `.php` to give a _resource path_ of
`/path/to/acme-log/src/Writer/FileWriter.php`.

5. The _scheme_ is searched. If the _resource path_ exists, it is
included, required, or otherwise loaded so that it is available.


## 4. Implementations

For example implementations of _conforming autoloaders_, please see the
[examples file][]. Example implementations MUST NOT be regarded as part of the
specification. They are examples only, and MAY change at any time.

[examples file]: psr-4-autoloader-examples.php
