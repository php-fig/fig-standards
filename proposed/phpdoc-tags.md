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
  - [5.3.  @copyright](#53-copyright)
  - [5.4.  @deprecated](#54-deprecated)
  - [5.5.  @internal](#55-internal)
  - [5.6.  @link](#56-link)
  - [5.7.  @method](#57-method)
  - [5.8.  @package](#58-package)
  - [5.9.  @param](#59-param)
  - [5.10. @property](#510-property)
  - [5.11. @return](#511-return)
  - [5.12. @see](#512-see)
  - [5.13. @since](#513-since)
  - [5.14. @throws](#514-throws)
  - [5.15. @todo](#515-todo)
  - [5.16. @uses](#516-uses)
  - [5.17. @var](#517-var)
  - [5.18. @version](#518-version)

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
  * [@author](#52-author)
  * [@copyright](#53-copyright)
  * [@version](#518-version)

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

* [@package](#58-package)

### 4.3.2. Function Or Method

In addition to the inherited descriptions and tags as defined in this chapter's
root, a function or method in a class or interface MUST inherit the following tags:

* [@param](#59-param)
* [@return](#511-return)
* [@throws](#514-throws)

### 4.3.3. Constant Or Property

In addition to the inherited descriptions and tags as defined in this chapter's
root, a constant or property in a class MUST inherit the following tags:

* [@var](#517-type)

## 5. Tags

Unless specifically mentioned in the description each tag MAY occur zero or more
times in each "DocBlock".

### 5.1. @api

The @api tag is used to highlight "Structural Elements" as being part of the
primary public API of a package.

#### Syntax

    @api

#### Description

The `@api` tag MAY be applied to public "Structural Elements" to highlight
them in generated documentation, pointing the consumer to the primary public
API components of a library or framework.

Other "Structural Elements" with a public visibility MAY be listed less
prominently in generated documentation.

See also the [`@internal`](#55-internal), which MAY be used to hide internal
API components from generated documentation.

#### Examples

```php
class UserService
{
    /**
     * This method is public-API. 
     *
     * @api
     */
    public function getUser()
    {
        <...>
    }

    /**
     * This method is "package scope", not public-API
     */
    public function callMefromAnotherClass()
    {
        <...>
    }
}
```

### 5.2. @author

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

### 5.3. @copyright

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

### 5.4. @deprecated

The @deprecated tag is used to indicate which 'Structural elements' are
deprecated and are to be removed in a future version.

#### Syntax

    @deprecated [<"Semantic Version">] [<description>]

#### Description

The @deprecated tag declares that the associated 'Structural elements' will
be removed in a future version as it has become obsolete or its usage is
otherwise not recommended, effective from the "Semantic Version" if provided.

This tag MAY provide an additional description stating why the associated
element is deprecated.

If the associated element is superseded by another it is RECOMMENDED to add a
@see tag in the same 'PHPDoc' pointing to the new element.

#### Examples

```php
/**
 * @deprecated
 *
 * @deprecated 1.0.0
 *
 * @deprecated No longer used by internal code and not recommended.
 *
 * @deprecated 1.0.0 No longer used by internal code and not recommended.
 */
```

### 5.5. @internal

The @internal tag is used to denote that the associated "Structural Element" is
a structure internal to this application or library. It may also be used inside
a description to insert a piece of text that is only applicable for
the developers of this software.

#### Syntax

    @internal [description]

or inline:

    {@internal [description]}

Contrary to other inline tags, the inline version of this tag may also contain
other inline tags (see second example below).

#### Description

The `@internal` tag indicates that the associated "Structural Element" is intended
only for use within the application, library or package to which it belongs.

Authors MAY use this tag to indicate that an element with public visibility should
be regarded as exempt from the API - for example:
  * Library authors MAY regard breaking changes to internal elements as being exempt
    from semantic versioning.
  * Static analysis tools MAY indicate the use of internal elements from another
    library/package with a warning or notice.

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
```

Include a note in the Description that only Developer Docs would show.

```php
/**
 * Counts the number of Foo.
 *
 * This method gets a count of the Foo.
 * {@internal Developers should note that it silently 
 *            adds one extra Foo (see {@link http://example.com}).}
 *
 * @return int Indicates the number of items.
 */
function count()
{
    <...>
}
```

### 5.6. @link

The @link tag indicates a custom relation between the associated
"Structural Element" and a website, which is identified by an absolute URI.

#### Syntax

    @link [URI] [description]

or inline

    {@link [URI] [description]}

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

### 5.7. @method

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

@method tags can ONLY be used in a PHPDoc that is associated with a
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
 * @method setInteger(int $integer)
 * @method string getString()
 * @method void setString(int $integer)
 */
class Child extends Parent
{
    <...>
}
```

### 5.8. @package

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

The package applies to that namespace, class or interface and their contained
elements. This means that a function which is contained in a namespace with the
@package tag assumes that package.

This tag MUST NOT occur more than once in a "DocBlock".

#### Examples

```php
/**
 * @package PSR\Documentation\API
 */
```

### 5.9. @param

The @param tag is used to document a single parameter of a function or method.

#### Syntax

    @param ["Type"] [name] [<description>]

#### Description

With the @param tag it is possible to document the type and function of a
single parameter of a function or method. When provided it MUST contain a
"Type" to indicate what is expected. The "name" is required only when some
@param tags are omitted due to all useful info already being visible in the
code signature itself. The description is OPTIONAL yet RECOMMENDED.

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

### 5.10. @property

The `@property` tag is used to declare which "magic" properties are supported.

#### Syntax

    @property[<-read|-write>] ["Type"] [name] [<description>]

#### Description

The `@property` tag is used when a `class` (or `trait`) implements the `__get()`
and/or `__set()` "magic" methods to resolve non-literal properties at run-time.

The `@property-read` and `@property-write` variants MAY be used to indicate "magic"
properties that can only be read or written.

The `@property` tags can ONLY be used in a PHPDoc that is associated with a
*class* or *trait*.

#### Example

In the following example, a class `User` implements the magic `__get()` method, in
order to implement a "magic", read-only `$full_name` property:

```php
/**
 * @property-read string $full_name
 */
class User
{
    /**
     * @var string
     */
    public $first_name;

    /**
     * @var string
     */
    public $last_name;

    public function __get($name)
    {
        if ($name === "full_name") {
            return "{$this->first_name} {$this->last_name}";
        }
    }
}
```

### 5.11. @return

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

### 5.12. @see

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

### 5.13. @since

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

### 5.14. @throws

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

### 5.15. @todo

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

### 5.16. @uses

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

### 5.17. @var

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
foreach ($connections as $sqlite) {
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

### 5.18. @version

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
[PHPDOC_PSR]:   https://github.com/php-fig/fig-standards/blob/master/proposed/phpdoc.md
