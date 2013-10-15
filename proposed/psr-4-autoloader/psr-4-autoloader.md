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

This specification is partly backwards incompatible with PSR-0. Where a
conflict occurs the rules in this specification precede, or override, the
rules in PSR-0.

This specification establishes a relationship between the fully qualified class
names of a library and the PHP files that contain their class definition. It
does so by first associating the autoloadable namespaces of the library with
their corresponding directories (rules 1-3). Then the specification describes
where a file that contains an autoloadable class should be located and how it
should be named (rules 4-5).

The goal for this specification is two-fold:

1. As an extension to PSR-0, and to provide an alternative option for
   applications to determine the location of a file resource on a medium,
   as supported by PHP, when a "Fully Qualified Class Name" is provided.

2. As a specification for software maintainers on how to name and structure
   namespaces in an application so that a uniform representation develops.

   This will aid developers by

   - improving navigation and searchability of PHP source code due to
     recognizability.
   - preventing conflicts and assisting with naming and structuring for
     maintainers.
   - assisting applications to automatically find classes without the need for
     include or require statements, also known as 'autoloading'.

The location of a file resource is often determined by a specialized library,
or component, to which we refer as an autoloader. This name provides no further
significance in the context of this document but is provided to clarify when
and where file resource locations are determined in real world situations.


2. Definitions
--------------

- **class**: The term _class_ refers to PHP classes, interfaces, and traits.

- **fully qualified class name**: A full namespace and class name, such as
  `Acme\Log\Writer\FileWriter`. A _fully qualified class name_ MUST NOT
  include a leading namespace separator.

- **namespace part**: The individual non-terminating parts of a _fully
  qualified class name_. Given a _fully qualified class name_ of
  `Acme\Log\Writer\FileWriter`, the _namespace parts_ are `Acme`, `Log`, and
  `Writer`. A _namespace part_ MUST NOT include a leading or trailing
  namespace separator.

- **class part**: The individual terminating part of a _fully qualified class
  name_. Given a _fully qualified class name_ of `Acme\Log\Writer\FileWriter`,
  the _class part_ is `FileWriter`. A _class part_ MUST NOT include a leading
  namespace separator.
  
- **namespace prefix**: One or more contiguous _namespace parts_ with
  namespace separators. Given a _fully qualified class name_ of
  `Acme\Log\Writer\FileWriter`, a _namespace prefix_ may be `Acme\`,
  `Acme\Log\`, or `Acme\Log\Writer\`. A _namespace prefix_ MUST NOT include a
  leading namespace separator, but MUST include a trailing namespace
  separator.

- **relative class name**: The parts of the _fully qualified class name_ that
  appear after the _namespace prefix_. Given a _fully qualified class name_ of
  `Acme\Log\Writer\FileWriter` and a _namespace prefix_ of `Acme\Log\`, the
  _relative class name_ is `Writer\FileWriter`. A _relative class name_ MUST
  NOT include a leading namespace separator.

- **base directory**: A directory path in the file system where files for
  _relative class names_ have their root. Given a namespace prefix of
  `Acme\Log\`, a _base directory_ could be `/path/to/acme-log/src`.
  A _base directory_ MUST include a trailing directory separator.

- **mapped file name**: The path in the file system resulting from the
  transformation of a _fully qualified class name_. Given a _fully qualified
  class name_ of `Acme\Log\Writer\FileWriter`, a _namespace prefix_ of
  `Acme\Log\`, and a _base directory_ of `/path/to/acme-log/src`, the
  _mapped file name_ MUST be `/path/to/acme-log/src/Writer/FileWriter.php`.

- "Fully Qualified Class Name" is a string representing the complete class
  name, including its Namespace. For the scope of this specification it
  MUST NOT include the leading Namespace separator. This is as opposed to
  the [Name Resolution Rules](http://php.net/manual/en/language.namespaces.rules.php)
  in the PHP manual.

- "Base Location" is a location from where an application or library should
  start scanning for a File Resource applicable to a given "Fully Qualified
  Class Name".

  A "Base Location" can be related to a "Namespace Prefix" in order to 'skip'
  "Namespace Parts" and to lessen the depth of the applicable File Resource
  Location.

  For example:

  > A "Base Location" can be the folder `./src/`; in which case attempting
  > to locate the "Fully Qualified Class Name"
  > `Acme\Log\Writer\FileWriter` will result in the File Resource
  > Location `./src/Acme/Log/Writer/FileWriter.php`.
  >
  > Should the "Base Location" `./src/` be related to the "Namespace
  > Prefix" `Acme\Log` then the File Resource Location would become
  > `./src/Writer/FileWriter.php`.
  >
  > See chapter 4 for more information on the translation process.

  The "Base Location" MUST include a trailing separator applicable to the
  respective location scheme; in case of physical files this means the
  directory separator.

- "Mapped File Location" is the end-product of the translation of a
  "Fully Qualified Class Name" and represents a File Resource Location.

- A "Library" is a directory which contains PHP code. A library can contain
  other libraries. In practice, multiple other terms exist for this or similar
  concepts, such as "Application", "Package" or "Module".

- "namespace": A PHP namespace, as is syntactically valid after the
  [PHP `namespace` keyword](http://www.php.net/manual/en/language.namespaces.definition.php).
  `\` by itself is not an acceptable _namespace_.

- "namespace separator": The PHP namespace separator symbol `\` (backslash).

- A "Fully Qualified Name" refers to the full identifier of a namespace or a
  class, as defined by
  [PHP's name resolution rules](http://php.net/manual/en/language.namespaces.rules.php).
  For the scope of this specification, fully qualified names always include the
  leading namespace separator.

- The "Unqualified Name" is the part of a fully qualified name that succeeds the
  last namespace separator. Given a fully qualified name of `\Foo\Bar\Baz\Qux`,
  the unqualified name is `Qux`.

- The "Parent Namespace" of a namespace is the part of a fully qualified
  namespace name that precedes the last namespace separator. Given a namespace
  of `\Foo\Bar\Baz\Qux`, the parent namespace is `\Foo\Bar\Baz`. Namespaces with
  only one namespace separator don't have a parent namespace.

- An "Autoloader" is a piece of PHP code that, when given the fully qualified
  name of a class, finds and loads the PHP file that contains the definition
  of that class.

- An "Autoloadable Class" is a class which is intended to be loaded by an
  autoloader ("autoloaded"). Classes which are not intended to be autoloaded
  by their developer are not covered by this term.

- An "Autoloadable Namespace" is a namespace which contains autoloadable
  classes.

- The "Corresponding Directory" of a namespace is the directory that contains
  the PHP files defining the autoloadable classes within that namespace. The
  location of that directory is given as a relative path from the root of the
  containing library.

- A "Base Namespace" within the scope of a library is a namespace for which a
  corresponding directory is explicitly defined.


3. Specification
----------------

For an application to be able to translate a "Fully Qualified Class Name" this
specification prescribes that specific principles, or conditions, MUST be
fulfilled or taken into account.

When these principles are met an application can translate the "Fully
Qualified Class Name" according to the rules as described in the appropriate
chapter below.

Aside from technical considerations this specification also imposes
requirements according to best practices on software developers. Software
developers that wish to comply with this specification MUST also write their
software using these same principles.

The principles are mentioned as the first chapter given that they are universal
to the way developers MUST deal with namespaces and "Fully Qualified Class
Names", the subsequent chapter will describe the process of translating a
namespace to a File Resource Location.


### 3.1. General

The _fully qualified class name_ MUST begin with a _namespace part_, which MAY
be followed by one or more additional _namespace parts_, and MUST end in a
_class part_.

At least one _namespace prefix_ of the _fully qualified class name_ MUST
correspond to a _base directory_.

A _namespace prefix_ MAY correspond to more than one _base directory_. (The
order in which a registered autoloader processes more than one corresponding
_base directory_ is undefined.)

1. A "Fully Qualified Class Name" MUST begin with one or more "Namespace Parts"
   and MUST end with a "Class" name.

   For example:

   > With a "Fully Qualified Class Name" of `Acme\Log\Baz`, the
   > "Namespace Parts" are `Acme\Log` and the "Class" name is `Baz`.

2. The first "Namespace Part" of a namespace or "Fully Qualified Class Name"
   MUST be a vendor name unique to the developer. This will prevent clashes
   between different pieces of software, such as libraries and components.

   It is RECOMMENDED for the second "Namespace Part" to be a unique name
   describing the application or library but this is not required.

   An example of this concept is:

   > `\Vendor\ClassName`

   or when the application name is used as second "Namespace Part":

   > `\Vendor\Application\ClassName`

3. This specification does not impose a limit on the number of
   "Namespace Parts" used by the developer of an application or library. It is
   however RECOMMENDED to limit the depth.

4. The File Location for a "Class" MUST match the capitalization of the
   "Class" and its "Namespace Parts".


### 3.2. Registered Autoloaders

"""Translating a "Fully Qualified Class Name" into a File Resource location"""

1. Registered autoloaders MUST transform the _fully qualified class name_ into
a _mapped file name_ as follows:

    a. The _namespace prefix_ portion of the _fully qualified class name_ MUST
    be replaced with the corresponding _base directory_.

    b. Each namespace separator in the _relative class name_ portion of the
    _fully qualified class name_ MUST be replaced with the
    `DIRECTORY_SEPARATOR` constant.

    c. The result MUST be suffixed with `.php`.

2. If the _mapped file name_ exists in the file system, the registered
autoloader MUST include or require it.

3. The registered autoloader MUST NOT throw exceptions, MUST NOT raise errors
of any level, and SHOULD NOT return a value.

A relationship MAY be present between a "Namespace Prefix" and a "Base
Location". This relationship allows an application or library to locate a
"Class" based on its "Fully Qualified Class Name". If no relationship is defined
then the "Namespace Prefix" is considered to be empty.

The method with which a relationship is defined is not within the scope
of this specification and is left up to the specific implementation.

To translate a "Fully Qualified Class Name" into a "Mapped File Location" an
application or library MUST apply the following steps, in sequence.

1. Preceding Namespace separators MUST be removed.

2. The "Namespace Prefix", when present, of a "Fully Qualified Class Name"
   MUST be replaced with the "Base Location" related to that "Namespace
   Prefix". If the "Namespace Prefix" is empty, then the "Base Location"
   is prepended to the "Fully Qualified Class Name"

3. All Namespace separators MUST be replaced with the separators for the
   respective location scheme; in case of physical files this means that the
   directory separators for the respective operating system MUST be used.

4. The result from the above rules MUST be suffixed with the string `.php`.

If the "Mapped File Location" is readable then the application or library MUST
include or require it.

For example:

> Given the "Fully Qualified Class Name" of
> `\Acme\Log\Writer\FileWriter`, the Namespace Prefix `Acme\Log\` and
> the "Base Location" `./src/` the following will happen:
>
> 1. `\Acme\Log\Writer\FileWriter` becomes
>    `Acme\Log\Writer\FileWriter`.
> 2. `Acme\Log\Writer\FileWriter` is changed into
>    `./src/Writer\FileWriter`.
> 3. The directory separators for `./src/Writer\FileWriter` are
>    replaced and the whole becomes `./src/Writer/FileWriter`.
> 4. Lastly the `.php` extension is added so that the "Mapped File Location"
>    will become `./src/Writer/FileWriter.php`.

A "Namespace Prefix" MAY have a relationship with more than one "Base
Location". When applicable, the application or library attempting to locate
a File Resource Location must search every "Base Location" until a readable
"Mapped File Location" is encountered.

The order in which an application will attempt to map a "Class" to
one of those locations is not within the scope of this specification.
Developers should be aware that different approaches MAY be used and SHOULD
refer to the documentation of the application with which they map "Classes"
to locations.

To prevent clashes and confusion with developers different "Namespace Prefixes"
SHOULD NOT map onto the same "Base Location".

An application or library MUST NOT throw exceptions or raise errors (of any
level) during the translation process. This will ensure that the process of
sequentially loading "Classes" is not abruptly stopped partway.


4. Implementations
------------------

Autoloaders are free to implement any algorithm of their choice to locate
files for the classes within an autoloadable library.

For example implementations, please see [the relevant wiki page][examples].
Example implementations MUST NOT be regarded as part of the specification.
They are examples only, and MAY change at any time.

[examples]: https://github.com/php-fig/fig-standards/wiki/PSR-4-Example-Implementations


5. Libraries
------------

A library is a PSR-4 compliant "Autoloadable Library" if it satisfies the
following rules:

1. The library MUST document how to find one or more corresponding directories
   for at least one namespace. Such a namespace then becomes a base namespace.
   The corresponding directory MAY be the library root itself.

   > *How* this correspondence is documented is up to the developer. Examples:
   >
   > * end user documentation
   > * composer.json
   > * PHP source code
   > * conventions (Drupal modules)

4. Each autoloadable class MUST belong to one of the base namespaces.

5. Each autoloadable class MUST be contained in a file located in the
   corresponding directory of the class' namespace. The file name MUST equal the
   class' unqualified name suffixed with `.php`.

   > Again we focus on autoloadable classes. A library may contain other classes
   > that don't satisfy this rule.

