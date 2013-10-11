PSR-4: Autoloader
=================

1. Introduction
---------------

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

2. Conventions used in this document
------------------------------------

The key words "MUST", "MUST NOT", "REQUIRED", "SHALL", "SHALL NOT", "SHOULD",
"SHOULD NOT", "RECOMMENDED", "MAY", and "OPTIONAL" in this document are to be
interpreted as described in [RFC 2119](http://tools.ietf.org/html/rfc2119).

3. Scope
--------

This specification is partly backwards incompatible with PSR-0. Where a
conflict occurs the rules in this specification precede, or override, the
rules in PSR-0.

4. Definitions
--------------

- "Class" refers to PHP classes, interfaces, and traits alike.

- "Fully Qualified Class Name" is a string representing the complete class
  name, including its Namespace. For the scope of this specification it
  MUST NOT include the leading Namespace separator.

- "Namespace Part" is any individual section of a "Fully Qualified Class
  Name" or Namespace.

  An example of this would be the "Fully Qualified Class Name"
  `Acme\Log\Formatter\LineFormatter`, where the "Namespace Parts" here are
  `Acme`, `Log`, and `Formatter`.

  A "Namespace Part" MUST NOT include a leading or trailing Namespace separator.

- "Namespace Prefix" is a series of "Namespace Parts", always starting with
  the first part, of a Namespace or "Fully Qualified Class Name".

  Example:

      Given a "Fully Qualified Class Name" of
      `Acme\Log\Formatter\LineFormatter`, the "Namespace Prefix" could be
      `Acme\`, `Acme\Log\`, or `Acme\Log\Formatter\`.

  A "Namespace Prefix" MUST NOT include a leading namespace separator, but
  MUST include a trailing namespace separator.

- "Base Location" is a location from where an application or library should
  start scanning for a File Resource applicable to a given "Fully Qualified
  Class Name".

  A "Base Location" can be related to a "Namespace Prefix" in order to 'skip'
  "Namespace Parts" and to lessen the depth of the applicable File Resource
  Location.

  For example:

      A "Base Location" can be the folder `./src/`; in which case attempting
      to locate the "Fully Qualified Class Name"
      `Acme\Log\Formatter\LineFormatter` will result in the File Resource
      Location `./src/Acme/Log/Formatter/LineFormatter.php`.

      Should the "Base Location" `./src/` be related to the "Namespace
      Prefix" `Acme\Log` then the File Resource Location would become
      `./src/Formatter/LineFormatter.php`.

      See chapter 4 for more information on the translation process.

  The "Base Location" MUST include a trailing separator applicable to the
  respective location scheme; in case of physical files this means the
  directory separator.

- "Mapped File Location" is the end-product of the translation of a
  "Fully Qualified Class Name" and represents a File Resource Location.

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

### 3.1. Principles

1. A "Fully Qualified Class Name" MUST begin with one or more "Namespace Parts"
   and MUST end with a "Class" name.

   For example:

       With a "Fully Qualified Class Name" of `Acme\Log\Baz`, the
       "Namespace Parts" are `Acme\Log` and the "Class" name is `Baz`.

2. The first "Namespace Part" of a namespace or "Fully Qualified Class Name"
   MUST be a vendor name unique to the developer. This will prevent clashes
   between different pieces of software, such as libraries and components.

   It is RECOMMENDED for the second "Namespace Part" to be a unique name
   describing the application or library but this is not required.

    An example of this concept is:

        `\Vendor\ClassName`

    or when the application name is used as second "Namespace Part":

        `\Vendor\Application\ClassName`

3. This specification does not impose a limit on the number of
   "Namespace Parts" used by the developer of an application or library. It is
   however RECOMMENDED to limit the depth.

4. The File Location for a "Class" MUST match the capitalization of the
   "Class" and its "Namespace Parts".

### Translating a "Fully Qualified Class Name" into a File Resource location

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

    Given the "Fully Qualified Class Name" of
    `\Acme\Log\Formatter\LineFormatter`, the Namespace Prefix `Acme\Log\` and
    the "Base Location" `./src/` the following will happen:

    1. `\Acme\Log\Formatter\LineFormatter` becomes
       `Acme\Log\Formatter\LineFormatter`.
    2. `Acme\Log\Formatter\LineFormatter` is changed into
       `./src/Formatter\LineFormatter`.
    3. The directory separators for `./src/Formatter\LineFormatter` are
       replaced and the whole becomes `./src/Formatter/LineFormatter`.
    4. Lastly the `.php` extension is added so that the "Mapped File Location"
       will become `./src/Formatter/LineFormatter.php`.

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
