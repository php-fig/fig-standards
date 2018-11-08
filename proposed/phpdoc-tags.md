PSR-19: PHPDoc tags
=============

## Table Of Contents

- [1. Introduction](#1-introduction)
- [2. Conventions Used In This Document](#2-conventions-used-in-this-document)
- [3. Definitions](#3-definitions)
- [4. Inheritance](#4-inheritance)
  - [4.1. Making inheritance explicit using the @inheritDoc tag](#41-making-inheritance-explicit-using-the-inheritdoc-tag)
  - [4.2. Using the {@inheritDoc} inline tag to augment a Description](#42-using-the-inheritdoc-inline-tag-to-augment-a-description)
  - [4.3. Element-specific inherited parts](#43-element-specific-inherited-parts)
    - [4.3.1. Class Or Interface](#431-class-or-interface)
    - [4.3.2. Function Or Method](#432-function-or-method)
    - [4.3.3. Constant Or Property](#433-constant-or-property)
- [5. Tags](#5-tags)
  - [5.1.  @api](#51-api)
  - [5.2.  @author](#52-author)
  - [5.3.  @category [deprecated]](#53-category-deprecated)
  - [5.4.  @copyright](#54-copyright)
  - [5.5.  @deprecated](#55-deprecated)
  - [5.6.  @example](#56-example)
  - [5.7.  @global](#57-global)
  - [5.8.  @internal](#58-internal)
  - [5.9.  @license](#59-license)
  - [5.10. @link](#510-link)
  - [5.11. @method](#511-method)
  - [5.12. @package](#512-package)
  - [5.13. @param](#513-param)
  - [5.14. @property](#514-property)
  - [5.15. @return](#515-return)
  - [5.16. @see](#516-see)
  - [5.17. @since](#517-since)
  - [5.18. @subpackage [deprecated]](#518-subpackage-deprecated)
  - [5.19. @throws](#519-throws)
  - [5.20. @todo](#520-todo)
  - [5.21. @uses](#521-uses)
  - [5.22. @var](#522-var)
  - [5.23. @version](#523-version)

## 1. Introduction

The main purpose of this PSR is to provide a complete catalog of Tags in
the [PHPDoc standard][PHPDOC_PSR].

This document SHALL NOT:

* Describe a catalog of Annotations.
* Describe best practices or recommendations for Coding Standards on the
  application of the PHPDoc standard. This document is limited to a formal
  specification of syntax and intention.

## 2. Conventions Used In This Document

The key words "MUST", "MUST NOT", "REQUIRED", "SHALL", "SHALL NOT", "SHOULD",
"SHOULD NOT", "RECOMMENDED", "MAY", and "OPTIONAL" in this document are to be
interpreted as described in [RFC 2119][RFC2119].

## 3. Definitions

See the Definitions section of the [PHPDoc PSR][PHPDOC_PSR], as those definitions
apply here as well.

## 4. Regarding Inheritance

A PHPDoc that is associated with a "Structural Element" that implements, extends
or overrides a "Structural Element" has the ability to inherit parts of
information from the PHPDoc associated with the "Structural Element" that is
implemented, extended or overridden.

The PHPDoc for every type of "Structural Element" MUST inherit the following
parts if that part is absent:

* [Summary]([PHPDOC_PSR]#51-summary)
* [Description]([PHPDOC_PSR]#52-description) and
* A specific subset of [Tags]([PHPDOC_PSR]#53-tags):
  * [@version](#525-version)
  * [@author](#52-author)
  * [@copyright](#54-copyright)

The PHPDoc for each type of "Structural Element" MUST also inherit a
specialized subset of tags depending on which "Structural Element" is
associated.

If a PHPDoc does not feature a part, such as Summary or Description, that is
present in the PHPDoc of a super-element, then that part is always implicitly
inherited.
The following is a list of all elements whose DocBlocks are able to inherit
information from a super-element's DocBlock:

1. a Class' or Interface's DocBlock can inherit information from a Class or
   Interface which it extends.
2. a Property's DocBlock can inherit information from a Property with the same
   name that is declared in a superclass.
3. a Method's DocBlock can inherit information from a Method with the same
   name that is declared in a superclass.
4. a Method's DocBlock can inherit information from a Method with the same
   name that is declared in an implemented interface in the current Class
   or that is implemented in a superclass.

> For example:
>
> Let's assume you have a method `\SubClass::myMethod()` and its class
> `\SubClass` extends the class `\SuperClass`. And in the class `\SuperClass`
> there is a method with the same name (e.g. `\SuperClass::myMethod`).
>
> If the above applies then the DocBlock of `\SubClass::myMethod()` will
> inherit any of the parts mentioned above from the PHPDoc of
> `\SuperClass::myMethod`. So if the `@version` tag was not redefined then it
> is assumed that `\SubClass::myMethod()` will have the same `@version`
> tag.

Inheritance takes place from the root of a class hierarchy graph to its leafs.
This means that anything inherited in the bottom of the tree MUST 'bubble' up
to the top unless overridden.

## 4.1. Making inheritance explicit using the @inheritDoc tag

Because inheritance is implicit it may happen that it is not necessary to
include a PHPDoc with a "Structural Element". This can cause confusion as it
is now ambiguous whether the PHPDoc was omitted on purpose or whether the
author of the code had forgotten to add documentation.

In order to resolve this ambiguity the `@inheritDoc` tag can be used to
indicate that this element will inherit its information from a super-element.

Example:

    /**
     * This is a summary.
     */
    class SuperClass
    {
    }

    /**
     * @inheritDoc
     */
    class SubClass extends SuperClass
    {
    }

In the example above the SubClass' Summary can be considered equal to that of
the SuperClass element, which is thus "This is a summary.".

## 4.2. Using the {@inheritDoc} inline tag to augment a Description

Sometimes you want to inherit the Description of a super-element and add your
own text with it to provide information specific to your "Structural Element".
This MUST be done using the `{@inheritDoc}` inline tag.

The `{@inheritDoc}` inline tag will indicate that at that location the
super-element's description MUST be injected or inferred.

Example:

    /**
     * This is the Summary for this element.
     *
     * {@inheritDoc}
     *
     * In addition this description will contain more information that
     * will provide a detailed piece of information specific to this
     * element.
     */

In the example above it is indicated that the Description of this PHPDoc is a
combination of the Description of the super-element, indicated by the
`{@inheritDoc}` inline tag, and the subsequent body text.

## 4.3. Element-specific inherited parts

### 4.3.1. Class Or Interface

In addition to the inherited descriptions and tags as defined in this chapter's
root, a class or interface MUST inherit the following tags:

* [@package](#512-package)

A class or interface SHOULD inherit the following deprecated tags if supplied:

* [@subpackage](#519-subpackage-deprecated)

The @subpackage MUST NOT be inherited if the @package name of the
super-class (or interface) is not the same as the @package of the child class
(or interface).

Example:

```php
/**
 * @package    Framework
 * @subpackage Controllers
 */
class Framework_ActionController
{
    <...>
}

/**
 * @package My
 */
class My_ActionController extends Framework_ActionController
{
    <...>
}
```

In the example above the My_ActionController MUST NOT inherit the subpackage
_Controllers_.

### 4.3.2. Function Or Method

In addition to the inherited descriptions and tags as defined in this chapter's
root, a function or method in a class or interface MUST inherit the following tags:

* [@param](#513-param)
* [@return](#515-return)
* [@throws](#520-throws)

### 4.3.3. Constant Or Property

In addition to the inherited descriptions and tags as defined in this chapter's
root, a constant or property in a class MUST inherit the following tags:

* [@var](#522-type)

## 5. Tags

Unless specifically mentioned in the description each tag MAY occur zero or more
times in each "DocBlock".

### 5.1. @api [WG++]

The @api tag is used to declare "Structural Elements" as being suitable for
consumption by third parties.

#### Syntax

    @api

#### Description

The @api tag represents those "Structural Elements" with a public visibility
which are intended to be the public API components for a library or framework.
Other "Structural Elements" with a public visibility serve to support the
internal structure and are not recommended to be used by the consumer.

The exact meaning of "Structural Elements" tagged with @api MAY differ per
project. It is however RECOMMENDED that all tagged "Structural Elements" SHOULD
NOT change after publication unless the new version is tagged as breaking
Backwards Compatibility.

#### Examples

```php
/**
 * This method will not change until a major release.
 *
 * @api
 *
 * @return void
 */
function showVersion()
{
   <...>
}
```

### 5.2. @author [WG++]

The @author tag is used to document the author of any "Structural Element".

#### Syntax

    @author [name] [<email address>]

#### Description

The @author tag can be used to indicate who has created a "Structural Element"
or has made significant modifications to it. This tag MAY also contain an
e-mail address. If an e-mail address is provided it MUST follow
the author's name and be contained in chevrons, or angle brackets, and MUST
adhere to the syntax defined in RFC 2822.

#### Examples

```php
/**
 * @author My Name
 * @author My Name <my.name@example.com>
 */
```

### 5.3. @category [deprecated]

The @category tag is used to organize groups of packages together but is
deprecated in favour of occupying the top-level with the @package tag.
As such, usage of this tag is NOT RECOMMENDED.

#### Syntax

    @category [description]

#### Description

The @category tag was meant in the original de-facto Standard to group several
@packages into one category. These categories could then be used to aid
in the generation of API documentation.

This was necessary since the @package tag as defined in the original Standard did
not contain more then one hierarchy level; since this has changed this tag SHOULD
NOT be used.

Please see the documentation for `@package` for details of its usage.

This tag MUST NOT occur more than once in a "DocBlock".

#### Examples

```php
/**
 * File-Level DocBlock
 *
 * @category MyCategory
 * @package  MyPackage
 */
```

### 5.4. @copyright [WG++]

The @copyright tag is used to document the copyright information of any
"Structural element".

#### Syntax

    @copyright <description>

#### Description

The @copyright tag defines who holds the copyright over the "Structural Element".
The copyright indicated with this tag applies to the "Structural Element" to
which it applies and all child elements unless otherwise noted.

The format of the description is governed by the coding standard of each
individual project. It is RECOMMENDED to mention the year or years which are
covered by this copyright and the organization involved.

#### Examples

```php
/**
 * @copyright 1997-2005 The PHP Group
 */
```

### 5.5. @deprecated

The @deprecated tag is used to indicate which 'Structural elements' are
deprecated and are to be removed in a future version.

#### Syntax

    @deprecated [<"Semantic Version">][:<"Semantic Version">] [<description>]

#### Description

The @deprecated tag declares that the associated 'Structural elements' will
be removed in a future version as it has become obsolete or its usage is
otherwise not recommended.

This tag MAY specify up to two version numbers in the sense of a version number
range:

The first version number, referred to as the 'starting version', denotes the
version in which the associated element has been deprecated.

The second version number, referred to as the 'ending version', denotes the
version in which the associated element is scheduled for removal.

If an 'ending version' has been specified, the associated 'Structural elements'
MAY no longer exist in the 'ending version' and MAY be removed without further
notice in that version or a later version, but MUST exist in all prior versions.

It is RECOMMENDED to specify both a 'starting version' and an 'ending version'.
In this case, the two version numbers MUST be separated by a colon (`:`) without
white-space in between.

The 'starting version' MAY be omitted. In this case, the 'ending version' MUST
be preceded by a colon.

This tag MAY provide an additional description stating why the associated
element is deprecated.

If the associated element is superseded by another it is RECOMMENDED to add a
@see tag in the same 'PHPDoc' pointing to the new element.

#### Examples

```php
/**
 * @deprecated
 *
 * @deprecated 1.0.0:2.0.0
 * @see \New\Recommended::method()
 *
 * @deprecated 1.0.0
 *
 * @deprecated :2.0.0
 *
 * @deprecated No longer used by internal code and not recommended.
 *
 * @deprecated 1.0.0 No longer used by internal code and not recommended.
 */
```

### 5.6. @example

The @example tag is used to link to an external source code file which contains
an example of use for the current "Structural element". An inline variant exists
with which code from an example file can be shown inline with the Description.

#### Syntax

    @example [URI] [<description>]

or inline:

    {@example [URI] [:<start>..<end>]}

#### Description

The example tag refers to a file containing example code demonstrating the
purpose and use of the current "Structural element". Multiple example tags may
be used per "Structural element" in case several scenarios are described.

The URI provided with the example tag is resolved according to the following
rules:

1. If a URI is proceeded by a scheme or root folder specifier such as `phar://`,
   `http://`, `/` or `C:\` then it is considered to be an absolute path.
2. If the URI is deemed relative and a location for the example files has been
   provided then the path relative to the given location is resolved.
3. If the previous path was not readable or the user has not provided a path
   then the application should try to search for a folder 'examples' in the
   same folder as the source file featuring the example tag. If found then an
   attempt to resolve the path by combining the relative path given in the
   example tag and the found folder should be made.
4. If the application was unable to resolve a path given the previous rules then
   it should check if a readable folder 'examples' is found in the root folder
   of the project containing the source file of the "Structural Element".

   > The root folder of a project is the highest folder common to all files
   > that are being processed by a consuming application.

If a consumer intends to display the contents of the example file then it is
RECOMMENDED to use a syntax highlighting solution to improve user experience.

The rules as described above also apply to the inline tags. The inline tag
has 2 additional parameters with which to limit which lines of code
are shown in the Description. Due to this, consuming applications MUST
show the example code in case an inline example tag is used.

The start and end argument may be omitted but the ellipsis should remain in
case either is used to give a clear visual indication. The same rules as
specified with the [substr][PHP_SUBSTR] function of PHP are in effect with
regards to the start and end limit.

> A consuming application MAY choose to support the limit format as used in the
> previous standard but it is deprecated per this PSR.
> The previous syntax was: {@example [URI] [<start>] [<end>]} and did not support
> the same rules as the substr function.

#### Examples

```php
/**
 * Counts the number of items.
 * {@example http://example.com/foo-inline.https:2..8}
 *
 * @example http://example.com/foo.phps
 *
 * @return int Indicates the number of items.
 */
function count()
{
    <...>
}
```

### 5.7. @global

TODO: The definition of this item should be discussed and whether it may or
may not be superseded in part or in whole by the @var tag.

The @global tag is used to denote a global variable or its usage.

#### Syntax

    @global ["Type"] [name]
    @global ["Type"] [description]

#### Description

Since there is no standard way to declare global variables, a @global tag MAY
be used in a DocBlock preceding a global variable's definition. To support
previous usages of @global, there is an alternate syntax that applies to
DocBlocks preceding a function, used to document usage of global
variables. In other words, there are two usages of @global: definition and
usage.

##### Syntax for the Global's Definition

Only one @global tag MAY be allowed per global variable DocBlock. A global
variable DocBlock MUST be followed by the global variable's definition before
any other element or DocBlock occurs.

The name MUST be the exact name of the global variable as it is declared in
the source.

##### Syntax for the Global's Usage

The function/method @global syntax MAY be used to document usage of global
variables in a function, and MUST NOT have a $ starting the third word. The
"Type" will be ignored if a match is made between the declared global
variable and a variable documented in the project.

#### Examples

(TODO: Examples for this tag should be added)

### 5.8. @internal

The @internal tag is used to denote that the associated "Structural Element" is
a structure internal to this application or library. It may also be used inside
a description to insert a piece of text that is only applicable for
the developers of this software.

#### Syntax

    @internal

or inline:

    {@internal [description]}

Contrary to other inline tags, the inline version of this tag may also contain
other inline tags (see example below).

Implementations MAY support two closing braces for the inline version,
due to [historical definition of the inline tag][INLINE_OLD] originally being:

    {@internal [description]}}

They MAY notify users that the two braces grammar is deprecated in favor
of using just one closing brace, since parsers/IDEs are now better at
recognizing matching pairs of open/close braces.

#### Description

The @internal tag can be used as counterpart of the @api tag, indicating that
the associated "Structural Element" is used purely for the internal workings of
this piece of software.

When generating documentation from PHPDoc comments it is RECOMMENDED to hide the
associated element unless the user has explicitly indicated that internal elements
should be included.

An additional use of @internal is to add internal comments or additional
description text inline to the Description. This may be done, for example,
to withhold certain business-critical or confusing information when generating
documentation from the source code of this piece of software.

#### Examples

Mark the count function as being internal to this project:

```php
/**
 * @internal
 *
 * @return int Indicates the number of items.
 */
function count()
{
    <...>
}

/**
 * Counts the number of Foo.
 *
 * {@internal Silently adds one extra Foo (see {@link http://example.com})}
 *
 * @return int Indicates the number of items.
 */
function count()
{
    <...>
}
```

### 5.9. @license

The @license tag is used to indicate which license is applicable for the
associated 'Structural Elements'.

#### Syntax

    @license [<SPDX identifier>|URI] [name]

#### Description

The @license tag provides licensing information to the user, which is applicable
to 'Structural Elements' and their child elements.

The first parameter MUST be either a 'SPDX identifier', as defined by the
[SPDX Open Source License Registry][SPDX], or a URL to a document containing
the full license text.

The second parameter MAY be the official name of the applicable license.

It is RECOMMENDED to only specify an 'SPDX identifier' and to apply @license
tags to file-level 'PHPDoc' only, since multiple varying licenses within a
single file may cause confusion with regard to which license applies at which
time.

In case multiple licenses apply, there MUST be one @license tag per applicable
license.

#### Examples

```php
/**
 * @license MIT
 *
 * @license GPL-2.0-or-later
 *
 * @license http://www.spdx.org/licenses/MIT MIT License
 */
```

### 5.10. @link

The @link tag indicates a custom relation between the associated
"Structural Element" and a website, which is identified by an absolute URI.

#### Syntax

    @link [URI] [description]

or inline

    @link [URI] [description]

#### Description

The @link tag can be used to define a relation, or link, between the
"Structural Element", or part of the description when used inline, to an URI.

The URI MUST be complete and well-formed as specified in [RFC 2396][RFC2396].

The @link tag MAY have a description appended to indicate the type of relation
defined by this occurrence.

#### Examples

```php
/**
 * @link http://example.com/my/bar Documentation of Foo.
 *
 * @return int Indicates the number of items.
 */
function count()
{
    <...>
}

/**
 * This method counts the occurrences of Foo.
 *
 * When no more Foo ({@link http://example.com/my/bar}) are given this
 * function will add one as there must always be one Foo.
 *
 * @return int Indicates the number of items.
 */
function count()
{
    <...>
}
```

### 5.11. @method

The @method allows a class to know which 'magic' methods are callable.

#### Syntax

    @method [return type] [name]([type] [parameter], [...]) [description]

#### Description

The @method tag is used in situation where a class contains the `__call()` magic
method and defines some definite uses.

An example of this is a child class whose parent has a `__call()` to have dynamic
getters or setters for predefined properties. The child knows which getters and
setters need to be present but relies on the parent class to use the `__call()`
method to provide it. In this situation, the child class would have a @method
tag for each magic setter or getter method.

The @method tag allows the author to communicate the type of the arguments and
return value by including those types in the signature.

When the intended method does not have a return value then the return type MAY
be omitted; in which case 'void' is implied.

@method tags MUST NOT be used in a PHPDoc that is not associated with a
*class* or *interface*.

#### Examples

```php
class Parent
{
    public function __call()
    {
        <...>
    }
}

/**
 * @method string getString()
 * @method void setInteger(int $integer)
 * @method setString(int $integer)
 */
class Child extends Parent
{
    <...>
}
```

### 5.12. @package

The @package tag is used to categorize "Structural Elements" into logical
subdivisions.

#### Syntax

    @package [level 1]\[level 2]\[etc.]

#### Description

The @package tag can be used as a counterpart or supplement to Namespaces.
Namespaces provide a functional subdivision of "Structural Elements" where the
@package tag can provide a *logical* subdivision in which way the elements can
be grouped with a different hierarchy.

If, across the board, both logical and functional subdivisions are equal it
is NOT RECOMMENDED to use the @package tag, to prevent maintenance overhead.

Each level in the logical hierarchy MUST separated with a backslash (`\`) to
be familiar to Namespaces. A hierarchy MAY be of endless depth but it is
RECOMMENDED to keep the depth at less or equal than six levels.

Please note that the @package applies to different "Structural Elements"
depending where it is defined.

1. If the @package is defined in the *file-level* DocBlock then it only applies
   to the following elements in the applicable file:
    * global functions
    * global constants
    * global variables
    * requires and includes

2. If the @package is defined in a *namespace-level* or *class-level* DocBlock
   then the package applies to that namespace, class or interface and their
   contained elements.
   This means that a function which is contained in a namespace with the
   @package tag assumes that package.

This tag MUST NOT occur more than once in a "DocBlock".

#### Examples

```php
/**
 * @package PSR\Documentation\API
 */
```

### 5.13. @param

The @param tag is used to document a single parameter of a function or method.

#### Syntax

    @param ["Type"] [name] [<description>]

#### Description

With the @param tag it is possible to document the type and function of a
single parameter of a function or method. When provided it MUST contain a
"Type" to indicate what is expected; the description on the other hand is
OPTIONAL yet RECOMMENDED. For complex structures such as option arrays it is
RECOMMENDED to use an "Inline PHPDoc" to describe the option array.

The @param tag MAY have a multi-line description and does not need explicit
delimiting.

It is RECOMMENDED when documenting to use this tag with every function and
method.

This tag MUST NOT occur more than once per parameter in a "PHPDoc" and is
limited to "Structural Elements" of type method or function.

#### Examples

```php
/**
 * Counts the number of items in the provided array.
 *
 * @param mixed[] $items Array structure to count the elements of.
 *
 * @return int Returns the number of elements.
 */
function count(array $items)
{
    <...>
}
```

The following example demonstrates the use of an "Inline PHPDoc" to document
an option array with two elements: 'required' and 'label'.

```php
/**
 * Initializes this class with the given options.
 *
 * @param array $options {
 *     @var bool   $required Whether this element is required
 *     @var string $label    The display name for this element
 * }
 */
public function __construct(array $options = array())
{
    <...>
}
```

### 5.14. @property

The @property tag allows a class to know which 'magic' properties are present.

#### Syntax

    @property ["Type"] [name] [<description>]

#### Description

The @property tag is used in the situation where a class contains the
`__get()` and `__set()` magic methods and allows for specific names.

An example of this is a child class whose parent has a `__get()`. The child
knows which properties need to be present but relies on the parent class to use the
`__get()` method to provide it.
In this situation, the child class would have a @property tag for each magic
property.

@property tags MUST NOT be used in a "PHPDoc" that is not associated with
a *class* or *interface*.

#### Examples

```php
class Parent
{
    public function __get()
    {
        <...>
    }
}

/**
 * @property string $myProperty
 */
class Child extends Parent
{
    <...>
}
```

### 5.15. @return

The @return tag is used to document the return value of functions or methods.

#### Syntax

    @return <"Type"> [description]

#### Description

With the @return tag it is possible to document the return type of a
function or method. When provided, it MUST contain a "Type" (See Appendix A)
to indicate what is returned; the description on the other hand is OPTIONAL yet
RECOMMENDED in case of complicated return structures, such as associative arrays.

The @return tag MAY have a multi-line description and does not need explicit
delimiting.

It is RECOMMENDED to use this tag with every function and method. An exception to
this recommendation, as defined by the Coding Standard of any individual project,
MAY be:

   **functions and methods without a `return` value**: the @return tag MAY be
   omitted here, in which case an interpreter MUST interpret this as if
   `@return void` is provided.

This tag MUST NOT occur more than once in a "DocBlock" and is limited to the
"DocBlock" of a "Structural Element" of a method or function.

#### Examples

```php
/**
 * @return int Indicates the number of items.
 */
function count()
{
    <...>
}

/**
 * @return string|null The label's text or null if none provided.
 */
function getLabel()
{
    <...>
}
```

### 5.16. @see

The @see tag indicates a reference from the associated "Structural Elements" to
a website or other "Structural Elements".

#### Syntax

    @see [URI | "FQSEN"] [<description>]

#### Description

The @see tag can be used to define a reference to other "Structural Elements"
or to a URI.

When defining a reference to another "Structural Elements" you can refer to a
specific element by appending a double colon and providing the name of that
element (also called the "FQSEN").

A URI MUST be complete and well-formed as specified in [RFC 2396][RFC2396].

The @see tag SHOULD have a description to provide additional information
regarding the relationship between the element and its target. Additionally, the
@see tag MAY have a tag specialization to add further definition to this
relationship.

#### Examples

```php
/**
 * @see number_of() :alias:
 * @see MyClass::$items           For the property whose items are counted.
 * @see MyClass::setItems()       To set the items for this collection.
 * @see http://example.com/my/bar Documentation of Foo.
 *
 * @return int Indicates the number of items.
 */
function count()
{
    <...>
}
```

### 5.17. @since

The @since tag is used to denote _when_ an element was introduced or modified,
using some description of "versioning" to that element.

#### Syntax

    @since [<"Semantic Version">] [<description>]

#### Description

Documents the "version" of the introduction or modification of any element.

It is RECOMMENDED that the version matches a semantic version number (x.x.x)
and MAY have a description to provide additional information.

This information can be used to generate a set of API Documentation where the
consumer is informed which application version is necessary for a specific
element.

The @since tag SHOULD NOT be used to show the current version of an element, the
@version tag MAY be used for that purpose.

#### Examples

```php
/**
 * This is Foo
 * @version 2.1.7 MyApp
 * @since 2.0.0 introduced
 */
class Foo
{
    /**
     * Make a bar.
     *
     * @since 2.1.5 bar($arg1 = '', $arg2 = null)
     *        introduced the optional $arg2
     * @since 2.1.0 bar($arg1 = '')
     *        introduced the optional $arg1
     * @since 2.0.0 bar()
     *        introduced new method bar()
     */
    public function bar($arg1 = '', $arg2 = null)
    {
        <...>
    }
}
```

### 5.18. @subpackage [deprecated]

The @subpackage tag is used to categorize "Structural Elements" into logical
subdivisions.

#### Syntax

    @subpackage [name]

#### Description

The @subpackage tag MAY be used as a counterpart or supplement to Namespaces.
Namespaces provide a functional subdivision of "Structural Elements" where
the @subpackage tag can provide a *logical* subdivision in which way the
elements can be grouped with a different hierarchy.

If, across the board, both logical and functional subdivisions are equal it is
NOT RECOMMENDED to use the @subpackage tag, to prevent maintenance overhead.

The @subpackage tag MUST only be used in a specific series of DocBlocks, as is
described in the documentation for the @package tag.

This tag MUST accompany a @package tag and MUST NOT occur more than once per
DocBlock.

#### Examples

```php
/**
 * @package PSR
 * @subpackage Documentation\API
 */
```

### 5.19. @throws

The @throws tag is used to indicate whether "Structural Elements" throw a
specific type of Throwable (exception or error).

#### Syntax

    @throws ["Type"] [<description>]

#### Description

The @throws tag MAY be used to indicate that "Structural Elements" throw a
specific type of error.

The type provided with this tag MUST represent an object that is a subtype of Throwable.

This tag is used to present in your documentation which error COULD occur and
under which circumstances. It is RECOMMENDED to provide a description that
describes the reason an exception is thrown.

It is also RECOMMENDED that this tag occurs for every occurrence of an
exception, even if it has the same type. By documenting every occurrence a
detailed view is created and the consumer knows for which errors to check.

#### Examples

```php
/**
 * Counts the number of items in the provided array.
 *
 * @param mixed[] $array Array structure to count the elements of.
 *
 * @throws InvalidArgumentException if the provided argument is not of type
 *     'array'.
 *
 * @return int Returns the number of elements.
 */
function count($items)
{
    <...>
}
```

### 5.20. @todo [WG++]

The @todo tag is used to indicate whether any development activities should
still be executed on associated "Structural Elements".

#### Syntax

    @todo [description]

#### Description

The @todo tag is used to indicate that an activity surrounding the associated
"Structural Elements" must still occur. Each tag MUST be accompanied by
a description that communicates the intent of the original author; this could
however be as short as providing an issue number.

#### Examples

```php
/**
 * Counts the number of items in the provided array.
 *
 * @todo add an array parameter to count
 *
 * @return int Returns the number of elements.
 */
function count()
{
    <...>
}
```

### 5.21. @uses

Indicates whether the current "Structural Element" consumes the
"Structural Element", or project file, that is provided as target.

#### Syntax

    @uses [file | "FQSEN"] [<description>]

#### Description

The `@uses` tag describes whether any part of the associated "Structural Element"
uses, or consumes, another "Structural Element" or a file that is situated in
the current project.

When defining a reference to another "Structural Element" you can refer to a
specific element by appending a double colon and providing the name of that
element (also called the "FQSEN").

Files that are contained in this project can be referred to by this tag. This
can be used, for example, to indicate a relationship between a Controller and
a template file (as View).

This tag MUST NOT be used to indicate relations to elements outside of the
system, so URLs are not usable. To indicate relations with outside elements the
@see tag can be used.

Applications consuming this tag, such as generators, are RECOMMENDED to provide
a `@used-by` tag on the destination element. This can be used to provide a
bi-directional experience and allow for static analysis.

#### Examples

```php
/**
 * @uses \SimpleXMLElement::__construct()
 */
function initializeXml()
{
    <...>
}
```

```php
/**
 * @uses MyView.php
 */
function executeMyView()
{
    <...>
}
```

### 5.22. @var

You may use the @var tag to document the "Type" of the following
"Structural Elements":

* Constants, both class and global scope
* Properties
* Variables, both global and local scope

#### Syntax

    @var ["Type"] [element_name] [<description>]

#### Description

The @var tag defines which type of data is represented by a value of a
Constant, Property or Variable.

Each Constant or Property definition or Variable where the type is ambiguous
or unknown SHOULD be preceded by a DocBlock containing the @var tag. Any
other variable MAY be preceded with a DocBlock containing the @var tag.

The @var tag MUST contain the name of the element it documents. An exception
to this is when property declarations only refer to a single property. In this
case the name of the property MAY be omitted.

This is used when compound statements are used to define a series of Constants
or Properties. Such a compound statement can only have one DocBlock while several
items are represented.

#### Examples

```php
/** @var int $int This is a counter. */
$int = 0;

// there should be no docblock here
$int++;
```

Or:

```php
class Foo
{
  /** @var string|null Should contain a description */
  protected $description = null;

  public function setDescription($description)
  {
      // there should be no docblock here
      $this->description = $description;
  }
}
```

Another example is to document the variable in a foreach explicitly; many IDEs
use this information to help you with auto-completion:

```php
/** @var \Sqlite3 $sqlite */
foreach($connections as $sqlite) {
    // there should be no docblock here
    $sqlite->open('/my/database/path');
    <...>
}
```

Even compound statements may be documented:

```php
class Foo
{
  protected
      /**
       * @var string Should contain a description
       */
      $name,
      /**
       * @var string Should contain a description
       */
      $description;

}
```

Or constants:

```php
class Foo
{
  const
      /**
       * @var string Should contain a description
       */
      MY_CONST1 = "1",
      /**
       * @var string Should contain a description
       */
      MY_CONST2 = "2";

}
```

### 5.23. @version

The @version tag is used to denote some description of "versioning" to an
element.

#### Syntax

    @version ["Semantic Version"] [<description>]

#### Description

Documents the current "version" of any element.

This information can be used to generate a set of API Documentation where the
consumer is informed about elements at a particular version.

It is RECOMMENDED that the version number matches a semantic version number as
described in the [Semantic Versioning Standard version 2.0][SEMVER2].

Version vectors from Version Control Systems are also supported, though they
MUST follow the form:

    name-of-vcs: $vector$

A description MAY be provided, for the purpose of communicating any additional
version-specific information.

The @version tag MAY NOT be used to show the last modified or introduction
version of an element, the @since tag SHOULD be used for that purpose.

#### Examples

```php
/**
 * File for class Foo
 * @version 2.1.7 MyApp
 *          (this string denotes the application's overall version number)
 * @version @package_version@
 *          (this PEAR replacement keyword expands upon package installation)
 * @version $Id$
 *          (this CVS keyword expands to show the CVS file revision number)
 */

/**
 * This is Foo
 */
class Foo
{
  <...>
}
```


[RFC2119]:      https://tools.ietf.org/html/rfc2119
[RFC2396]:      https://tools.ietf.org/html/rfc2396
[SEMVER2]:      http://www.semver.org
[PHP_SUBSTR]:   https://php.net/manual/function.substr.php
[SPDX]:         https://www.spdx.org/licenses
[INLINE_OLD]:   https://manual.phpdoc.org/HTMLframesConverter/default/phpDocumentor/tutorial_tags.inlineinternal.pkg.html
[PHPDOC_PSR]:   https://github.com/php-fig/fig-standards/blob/master/proposed/phpdoc.md


[WG++] denotes "section approved by Working Group"
