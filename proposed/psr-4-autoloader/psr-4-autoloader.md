# PSR-4: Autoloader

The key words "MUST", "MUST NOT", "REQUIRED", "SHALL", "SHALL NOT", "SHOULD",
"SHOULD NOT", "RECOMMENDED", "MAY", and "OPTIONAL" in this document are to be
interpreted as described in [RFC 2119](http://tools.ietf.org/html/rfc2119).


## 1. Overview

This PSR describes a technique to [autoload][] classes from specified file
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
  `\Acme\Log\Writer\FileWriter` including the leading namespace
  separator.

- **autoloadable class name**: Any class intended for autoloading. (Classes
  not intended for autoloading are not covered by this term.) The
  _autoloadable class name_ is the same as the _fully qualified class name_
  but will not include the leading namespace separator. Given a _fully
  qualified class name_ of `\Acme\Log\Writer\FileWriter`, the _autoloadable
  class name_ is `Acme\Log\Writer\FileWriter`.
  
- **namespace part**: The individual non-terminating parts of an _autoloadable
  class name_. Given an _autoloadable class name_ of
  `Acme\Log\Writer\FileWriter`, the _namespace parts_ are `Acme`, `Log`, and
  `Writer`. A _namespace part_ has no leading or trailing namespace separator.

- **class part**: The individual terminating part of an _autoloadable class
  name_. Given an _autoloadable class name_ of `Acme\Log\Writer\FileWriter`,
  the _class part_ is `FileWriter`, without a leading namespace separator.

- **namespace prefix**: One or more contiguous leading _namespace parts_ with
  namespace separators. Given an _autoloadable class name_ of
  `Acme\Log\Writer\FileWriter`, a _namespace prefix_ may be `Acme\`,
  `Acme\Log\`, or `Acme\Log\Writer\`. A _namespace prefix_ will include a
  leading namespace separator, but will not include trailing namespace separator.

- **relative class name**: The parts of the _autoloadable class name_ that
  appear after the _namespace prefix_. Given an _autoloadable class name_ of
  `Acme\Log\Writer\FileWriter` and a _namespace prefix_ of `Acme\Log\`, the
  _relative class name_ is `Writer\FileWriter`. A _relative class name_ MUST
  NOT include a leading namespace separator.

- **class file**: A file that, if included in a PHP script, will cause this
  class to be defined.

- **file path**: A string intended to address a file.  
  (It is still a _file path_, even if the file does not exist or is not a file.)

- **base path**: A string intended to represent a directory, with an appended
  directory separator.  
  (It is still a _base path_, even if the directory does not exist, or it is not
  a directory)

- **conforming autoloader**: PHP autoloader code that implements follows these 
  definitions and attempts to inlcude the correct _class file_ based on 
  a valid _fully qualified class name_.


## 3. Specification

### 3.1. Preamble

For a _conforming autoloader_ to be able to transform an _autoloadable class
name_ into a _file path_, this specification describes a technique that
MUST be applied or taken into account. When the technique is applied, the
_conforming autoloader_ can autoload an _autoloadable class name_ from an
existing _class file_.

Aside from technical considerations, this specification also imposes
requirements on developers who want their classes to be autoloadable by a
_conforming autoloader_. Developers who wish to comply with this specification
MUST structure their classes using these same principles.

### 3.2. Requirements

This is a collection of rules which explain how the _FQCN_ can be 
converted into the _file path_ to a _class file_.

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
correspond to a _base path_, using the following rules:

    a. A _namespace prefix_ MAY correspond to more than one _base path_.
    (The order in which a _conforming autoloader_ processes more than one
    corresponding _base path_ is outside the scope of this spec.)

    b. To prevent conflicts, different _namespace prefixes_ SHOULD NOT
    correspond to the same _base path_.

3. The files MUST be laid out so that an autoloader can perform the
following steps to locate and eventually include the correct _class file_:

  1. For each _namespace prefix_ of the _autoloadable class name_, determine
  all _base paths_ associated with it, if any.

  2. For every combination of _namespace prefix_ and _directory_ found,
  take the _relative class name_  replace every _namespace separator_ in
  it with a directory separator. Append the ".php" suffix, and append the result
  to the _base path_, to obtain a _file path_.
  
  3. If any of the _file paths_ obtained this way points to an existing file,
  then include or require exactly one of these files.


## 4. Implementations

1. A _conforming autoloader_ will not interfere with other autoloaders, and as
such it will not throw exceptions, raise errors of any level, and should not
return a value.

2. Developers who want their classes to be autoloadable by a _conforming
autoloader_ will specify how their _namespace prefixes_ correspond to
_base paths_. The approach is left to the autoloader developer. It may
be via narrative documentation, meta-files, PHP source code, project-specific
conventions, or some other approach.

3. The order in which a _conforming autoloader_ attempts to process
multiple _base paths_ corresponding to a _namespace prefix_ is not
within the scope of this specification. Refer to the documentation of
the _conforming autoloader_ for more information.

## 5. Examples

The following examples MUST NOT be regarded as part of the specification. 

### 5.1. Example Technique

The aim of this "Example Technique" is to highlight how an autoloader could 
transform a _autoloadable class name_ into a _class file path_.

Given a UNIX-like file system _scheme_, a _fully qualified class name_ of
`\Acme\Log\Writer\FileWriter`, a _namespace prefix_ of `Acme\Log\`, and a
_base path_ of `/path/to/acme-log/src/`, the above specification will
result in the following actions by a _conforming autoloader_:

1. `\Acme\Log\Writer\FileWriter` becomes `Acme\Log\Writer\FileWriter`.

2. The _namespace prefix_ is replaced with the _base path_. That is,
`Acme\Log\Writer\FileWriter` is transformed into
`/path/to/acme-log/src/Writer\FileWriter`.

3. The _namespace separators_ in the _relative class name_ are replaced with
_scheme_-appropriate separators. That is,
`/path/to/acme-log/src/Writer\FileWriter` is tranformed into
`/path/to/acme-log/src/Writer/FileWriter`.

4. The result is appended with `.php` to give a _file path_ of
`/path/to/acme-log/src/Writer/FileWriter.php`.

5. If the _file path_ points to an existing file, it is
included, required, or otherwise loaded so that it is available.


### 5.2. Example File Organization

The above specification implies a particular organizational structure for
class files. Developers MUST use the following process to determine where
a class file will be placed:

1. Pick a single _namespace prefix_ for the classes to be autoloaded.

2. Pick one or more _base paths_ for the file locations.

3. Remove the _namespace prefix_ from the _autoloadable class name_.
    
4. The remaining _namespace parts_ become subdirectories under one of the
_base paths_.

5. The remaining _class part_ becomes the file name and is suffixed with
`.php`.

For example, given:

- _autoloadable class names_ of `Acme\Log\Writer\FileWriter` and
  `Acme\Log\Writer\FileWriterTest`,

- a _namespace prefix_ of `Acme\Log`,

- a _base path_ of `/path/to/acme-log/src/` for source files,

- a _base path_ of `/path/to/acme-log/tests/` for test files,

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
