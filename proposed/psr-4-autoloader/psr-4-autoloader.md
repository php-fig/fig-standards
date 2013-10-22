# PSR-4: Autoloader

The key words "MUST", "MUST NOT", "REQUIRED", "SHALL", "SHALL NOT", "SHOULD",
"SHOULD NOT", "RECOMMENDED", "MAY", and "OPTIONAL" in this document are to be
interpreted as described in [RFC 2119](http://tools.ietf.org/html/rfc2119).


## 1. Overview

This PSR describes the mandatory requirements that must be adhered to for 
[autoloader][autoload] interoperability between multiple conforming 
autoloaders, by describing how to name and structure classes. It is fully 
interoperable, and can be used in addition to any other autoloading technique, 
including [PSR-0][]. 

[autoload]: http://php.net/autoload
[PSR-0]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-0.md

## 2. Definitions

- **Class**: The term _class_ refers to PHP classes, interfaces, traits, and
  similar future resource definitions.

- **Namespace**: A PHP namespace, as is syntactically valid after the
  [PHP `namespace` keyword](http://www.php.net/manual/en/language.namespaces.definition.php).
  `\` by itself is not an acceptable _namespace_ within this PSR.

- **Namespace Separator**: A symbol that separates a PHP namespace, i.e: 
  a `\` (backslash).

- **Fully Qualified Class Name**: A full namespace and class name, such as
  `\Acme\Log\Writer\FileWriter` including the leading namespace
  separator.

- **Autoloadable Class Name**: Any class intended for autoloading. (Classes
  not intended for autoloading are not covered by this term.) The
  _autoloadable class name_ is the same as the _fully qualified class name_
  but will not include the leading _namespace separator_. Given a _fully
  qualified class name_ of `\Acme\Log\Writer\FileWriter`, the _autoloadable
  class name_ is `Acme\Log\Writer\FileWriter`.
  
- **Namespace Part**: The individual non-terminating parts of an _autoloadable
  class name_. Given an _autoloadable class name_ of
  `Acme\Log\Writer\FileWriter`, the _namespace parts_ are `Acme`, `Log`, and
  `Writer`. A _namespace part_ has no leading or trailing _namespace separator_.

- **Class Part**: The individual terminating part of an _autoloadable class
  name_. Given an _autoloadable class name_ of `Acme\Log\Writer\FileWriter`,
  the _class part_ is `FileWriter`, without a leading _namespace separator_.

- **Namespace Prefix**: One or more contiguous leading _namespace parts_ with
  _namespace separators_. Given an _autoloadable class name_ of
  `Acme\Log\Writer\FileWriter`, a _namespace prefix_ may be `Acme\`,
  `Acme\Log\`, or `Acme\Log\Writer\`. A _namespace prefix_ will include a
  trailing _namespace separator_, but will not include leading _namespace 
  separator_.

- **Relative Class Name**: The parts of the _autoloadable class name_ that
  appear after the _namespace prefix_. Given an _autoloadable class name_ of
  `Acme\Log\Writer\FileWriter` and a _namespace prefix_ of `Acme\Log\`, the
  _relative class name_ is `Writer\FileWriter`. A _relative class name_ will
  not include a leading _namespace separator_.

- **Base Directory**: The directory path in a file system where the files 
  for "relative class names" have their root. Given a _namespace prefix_ of
  `Acme\Log\`, a _base directory_ could be `/path/to/acme-log/src/`. A _base
  directory_ will include a trailing directory separator, and could include 
  a leading directory separator. For example, in most file systems, that 
  directory separator could be "\" or "/".

- **Mapped File Name**: The path in a file system resulting from the 
  transformation of a "fully qualified class name". Given an _autoloadable 
  class name_ of `Acme\Log\Writer\FileWriter`, a _namespace prefix_ of 
  `Acme\Log\`, a UNIX-like file system and a _base directory_ of
  `/path/to/acme-log/src` the _mapped file name_ will be 
  `/path/to/acme-log/src/Writer/FileWriter.php`. The _mapped file name_ 
  is not certain to exist in the target file system.

- **Conforming Autoloader**: PHP autoloader code that implements follows these 
  definitions and attempts to include the correct _mapped file name_ based on 
  a valid _fully qualified class name_.


## 3. Specification

### 3.1. Preamble

For a _conforming autoloader_ to be able to transform an _autoloadable class
name_ into a _mapped file name_, this specification describes a technique that
MUST be applied or taken into account. When the technique is applied, the
_conforming autoloader_ can autoload an _autoloadable class name_ from an
existing _mapped file name_.

Aside from technical considerations, this specification also imposes
requirements on developers who want their classes to be autoloadable by a
_conforming autoloader_. Developers who wish to comply with this specification
MUST structure their classes using these same principles.

### 3.2. Requirements

This is a collection of rules which explain how the _autoloadable class name_
can be converted into a _mapped file name_.

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

    > **Example:** The _autoloadable class name_ which contains a "vendor 
    name" and other _namespace parts_ - including potentially a "package name",
    could follow this structure `\<Vendor Name>\(<Namespace Parts>\)*<Class Part>`.

2. At least one _namespace prefix_ of each _autoloadable class name_ MUST
correspond to a _base directory_, using the following rules:

    a. A _namespace prefix_ MAY correspond to more than one _base directory_.
    (The order in which a _conforming autoloader_ processes more than one
    corresponding _base directory_ is outside the scope of this spec.)

    b. To prevent conflicts, different _namespace prefixes_ SHOULD NOT
    correspond to the same _base directory_.

3. The resources MUST be laid out so that an autoloader can perform the
following steps to locate and eventually include the correct _resource_:

  1. For each _namespace prefix_ of the _autoloadable class name_, determine
  all _base directories_ associated with it, if any.

  2. For every combination of _namespace prefix_ and _base directory_ found,
  take the _relative class name_  replace every _namespace separator_ in
  it with an appropriate directory separator. Append the ".php" suffix, and 
  append the result to the _base directory_. The result will be refered to 
  as _mapped file name_.
  
  3. If any of the _mapped file names_ obtained this way exists in the target
  file system, then include or require exactly one of them.


## 4. Implementations

1. A _conforming autoloader_ will not interfere with other autoloaders, and as
such it will not throw exceptions, raise errors of any level, and should not
return a value.

2. Developers who want their classes to be autoloadable by a _conforming
autoloader_ will specify how their _namespace prefixes_ correspond to
_base directories_. The approach is left to the autoloader developer. It may
be via narrative documentation, meta-files, PHP source code, project-specific
conventions, or some other approach.

3. The order in which a _conforming autoloader_ attempts to process
multiple _base directories_ corresponding to a _namespace prefix_ is not
within the scope of this specification. Refer to the documentation of
the _conforming autoloader_ for more information.

## 5. Examples

The following examples MUST NOT be regarded as part of the specification.

### 5.1. Example Technique

The aim of this "Example Technique" is to highlight how an autoloader could 
transform a _autoloadable class name_ into a _mapped file name_.

Given a UNIX-like file system, a _fully qualified class name_ of
`\Acme\Log\Writer\FileWriter`, a _namespace prefix_ of `Acme\Log\`, and a
_base directory_ of `/path/to/acme-log/src/`, the above specification will
result in the following actions by a _conforming autoloader_:

1. `\Acme\Log\Writer\FileWriter` becomes `Acme\Log\Writer\FileWriter`.

2. The _namespace prefix_ is replaced with the _base directory_. That is,
`Acme\Log\Writer\FileWriter` is transformed into
`/path/to/acme-log/src/Writer\FileWriter`.

3. The _namespace separators_ in the _relative class name_ are replaced with
appropriate directory separators. That is,
`/path/to/acme-log/src/Writer\FileWriter` is tranformed into
`/path/to/acme-log/src/Writer/FileWriter`.

4. The result is appended with `.php` to give a _mapped file name_ of
`/path/to/acme-log/src/Writer/FileWriter.php`.

5. The target file system is searched. If a _mapped file name_ exists, it is
included, required, or otherwise loaded so that it is available.


### 5.2. Example Resource Organization

The above specification implies a particular organizational structure for
class files. Developers MUST use the following process to determine where
a class file will be placed:

1. Pick a single _namespace prefix_ for the classes to be autoloaded.

2. Pick one or more _base directories_ for the file locations.

3. Remove the _namespace prefix_ from the _autoloadable class name_.
    
4. The remaining _namespace parts_ become subdirectories under one of the
_base directories_.

5. The remaining _class part_ becomes the file name and is suffixed with
`.php`.

For example, given:

- _autoloadable class names_ of `Acme\Log\Writer\FileWriter` and
  `Acme\Log\Writer\FileWriterTest`,

- a _namespace prefix_ of `Acme\Log`,

- a _base directory_ of `/path/to/acme-log/src/` for source files,

- a _base directory_ of `/path/to/acme-log/tests/` for test files,

... the resulting files MUST be organized like this:

    /path/to/acme-log/
        src/
            Writer/
                FileWriter.php
        tests/
            Writer/
                FileWriterTest.php

### 5.3. Example Implementation

For example implementations of _conforming autoloaders_, please see the
[examples file][]. Example implementations MUST NOT be regarded as part of the
specification and MAY change at any time.

[examples file]: psr-4-autoloader-examples.php
