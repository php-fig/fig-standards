PSR-5: PHPDoc
=============

## Table Of Contents

- [1. Introduction](#1-introduction)
- [2. Conventions Used In This Document](#2-conventions-used-in-this-document)
- [3. Definitions](#3-definitions)
- [4. Basic Principles](#4-basic-principles)
- [5. The PHPDoc Format](#5-the-phpdoc-format)
  - [5.1. Summary](#51-summary)
  - [5.2. Description](#52-description)
  - [5.3. Tags](#53-tags)
    - [5.3.1. Tag Name](#531-tag-name)
    - [5.3.2. Tag Specialization](#532-tag-specialization)
    - [5.3.3. Tag Signature](#533-tag-signature)
  - [5.4. Inline PHPDoc](#54-inline-phpdoc)
  - [5.5. Examples](#55-examples)
- [6. Inheritance](#6-inheritance)
  - [6.1. Making inheritance explicit using the @inheritDoc tag](#61-making-inheritance-explicit-using-the-inheritdoc-tag)
  - [6.2. Using the {@inheritDoc} inline tag to augment a Description](#62-using-the-inheritdoc-inline-tag-to-augment-a-description)
  - [6.3. Element-specific inherited parts]
    - [6.3.1. Class Or Interface](#61-class-or-interface)
    - [6.3.2. Function Or Method](#62-function-or-method)
    - [6.3.3. Constant Or Property](#63-constant-or-property)
- [7. Tags](#7-tags)
  - [7.1.  @api](#71-api)
  - [7.2.  @author](#72-author)
  - [7.3.  @category [deprecated]](#73-category-deprecated) 
  - [7.4.  @copyright](#74-copyright)
  - [7.5.  @deprecated](#75-deprecated)
  - [7.6.  @example](#76-example)
  - [7.7.  @global](#77-global)
  - [7.8.  @internal](#78-internal)
  - [7.9.  @license](#79-license)
  - [7.10. @link [deprecated]](#710-link-deprecated)
  - [7.11. @method](#711-method)
  - [7.12. @package](#712-package)
  - [7.13. @param](#713-param)
  - [7.14. @property](#714-property)
  - [7.15. @return](#715-return)
  - [7.16. @see](#716-see)
  - [7.17. @since](#717-since)
  - [7.18. @subpackage [deprecated]](#719-subpackage-deprecated)
  - [7.19. @throws](#720-throws)
  - [7.20. @todo](#721-todo)
  - [7.21. @uses](#723-uses)
  - [7.22. @var](#724-var)
  - [7.23. @version](#725-version)
- [Appendix A. Types](#appendix-a-types)
  - [ABNF](#abnf)
  - [Details](#details)
  - [Valid Class Name](#valid-class-name)
  - [Keyword](#keyword)
- Appendix B. Differences Compared With The De-facto PHPDoc Standard


## 1. Introduction

The main purpose of this PSR is to provide a complete and formal definition of
the PHPDoc standard. This PSR deviates from its predecessor, the de-facto PHPDoc
Standard associated with [phpDocumentor 1.x][PHPDOC.ORG], to provide
support for newer features in the PHP language and to address some of the
shortcomings of its predecessor.

This document SHALL NOT:

* Describe a standard for implementing annotations via PHPDoc. Although it does
  offer versatility which makes it possible to create a subsequent PSR based on
  current practices. See [chapter 5.3](#53-tags) for more information on this
  topic.
* Describe best practices or recommendations for Coding Standards on the
  application of the PHPDoc standard. This document is limited to a formal
  specification of syntax and intention.

## 2. Conventions Used In This Document

The key words "MUST", "MUST NOT", "REQUIRED", "SHALL", "SHALL NOT", "SHOULD",
"SHOULD NOT", "RECOMMENDED", "MAY", and "OPTIONAL" in this document are to be
interpreted as described in [RFC 2119][RFC2119].

## 3. Definitions

* "PHPDoc" is a section of documentation which provides information on aspects 
  of a "Structural Element".

  > It is important to note that a PHPDoc and a DocBlock are two separate
  > entities. The DocBlock is the combination of a DocComment, which is a type
  > of comment, and a PHPDoc entity. It is the PHPDoc entity that contains the
  > syntax as described in this specification such as the description and tags.

* "Structural Element" is a collection of Programming Constructs which MAY be
  preceded by a DocBlock. The collection contains the following constructs:

  * file
  * require(_once)
  * include(_once)
  * class
  * interface
  * trait
  * function (including methods)
  * property
  * constant
  * variables, both local and global scope.

  It is RECOMMENDED to precede a "Structural Element" with a DocBlock where it is
  defined and not with each usage. It is common practice to have the DocBlock
  precede a Structural Element but it MAY also be separated by a an undetermined 
  number of empty lines.

  Example:

  ```php
  /** @var int $int This is a counter. */
  $int = 0;


  // there should be no docblock here
  $int++;
  ```

  or

  ```php
    /**
     * This class acts as an example on where to position a DocBlock.
     */
    class Foo
    {
        /** @var string|null $title contains a title for the Foo with a max. length of 24 characters */
        protected $title = null;


        /**
         * Sets a single-line title.
         *
         * @param string $title A text with a maximum of 24 characters.
         *
         * @return void
         */
        public function setTitle($title)
        {
            // there should be no docblock here
            $this->title = $title;
        }
    }
  ```

  An example of use that falls beyond the scope of this Standard is to document
  the variable in a foreach explicitly; several IDEs use this information to
  assist their auto-completion functionality.

  This Standard does not cover this specific instance as a `foreach` statement
  is not considered to be a "Structural Element" but a Control Flow statement.

  ```php
  /** @var \Sqlite3 $sqlite */
  foreach($connections as $sqlite) {
      // there should be no docblock here
      $sqlite->open('/my/database/path');
      <...>
  }
  ```

* "DocComment" is a special type of comment which MUST

  - start with the character sequence `/**` followed by a whitespace character
  - end with `*/` and
  - have zero or more lines in between.

  In case a DocComment spans multiple lines then every line MUST start with
  an asterisk (`*`) that SHOULD be aligned with the first asterisk of the
  opening clause.

  Single line example:

  ```php
  /** <...> */
  ```

  Multiline example:

  ```php
    /**
     * <...>
     */
  ```

* "DocBlock" is a "DocComment" containing a single "PHPDoc" structure and
  represents the basic in-source representation.

* "Tag" is a single piece of meta information regarding a "Structural Element"
  or a component thereof.

* "Inline PHPDoc" is a "PHPDoc" that is related to a "Tag" instead of a
  "Structural element". It replaces the description part of the "Tag".

* "Type" is the determination of what type of data is associated with an element.
  This is commonly used when determining the exact values of arguments, constants,
  properties and more.

  See Appendix A for more detailed information about types.

* "Semantic Version" refers to the definition as set in the [Semantic Versioning
  Specification 2.0.0][SEMVER2].

* "FQSEN" is an abbreviation for Fully Qualified Structural Element Name. This
  notation expands on the Fully Qualified Class Name and adds a notation to
  identify class/interface/trait members and re-apply the principles of the FQCN
  to Interfaces, Traits, Functions and global Constants.

  The following notations can be used per type of "Structural Element":

  *Namespace*:      `\My\Space`
  *Function*:       `\My\Space\myFunction()`
  *Constant*:       `\My\Space\MY_CONSTANT`
  *Class*:          `\My\Space\MyClass`
  *Interface*:      `\My\Space\MyInterface`
  *Trait*:          `\My\Space\MyTrait`
  *Method*:         `\My\Space\MyClass::myMethod()`
  *Property*:       `\My\Space\MyClass::$my_property`
  *Class Constant*: `\My\Space\MyClass::MY_CONSTANT`

  A FQSEN has the following [ABNF][RFC5234]
  definition:

          FQSEN    = fqnn / fqcn / constant / method / property  / function
          fqnn     = "\" [name] *("\" [name])
          fqcn     = fqnn "\" name
          constant = (fqnn "\" / fqcn "::") name
          method   = fqcn "::" name "()"
          property = fqcn "::$" name
          function = fqnn "\" name "()"
          name     = (ALPHA / "_") *(ALPHA / DIGIT / "_")

## 4. Basic Principles

* A PHPDoc MUST always be contained in a "DocComment"; the combination of these
  two is called a "DocBlock".

* A DocBlock MUST directly precede a "Structural Element"

  > An exception to this principle is the File-level DocBlock which MUST be
  > placed at the top of a PHP source code file as the first DocBlock in a 
  > file. 
  >
  > To prevent ambiguity when a Structural Element comes directly after a
  > File-level DocBlock MUST that element have its own DocBlock in 
  > addition to the File-level DocBlock.
  >
  > Example of a valid File-level DocBlock:
  >
  > ``` 
  > <?php
  > /**
  >  * This is a file-level DocBlock
  >  */
  >  
  > /**
  >  * This is a class DocBlock
  >  */
  > class MyClass {
  > }
  > ```
  >
  > Example of an invalid File-level DocBlock
  > 
  > ```
  > <?php
  > /**
  >  * This is a class DocBlock
  >  */
  > class MyClass {
  > }
  > ```

## 5. The PHPDoc Format

The PHPDoc format has the following [ABNF][RFC5234]
definition:

    PHPDoc             = [summary] [description] [tags]
    inline-phpdoc      = "{" *SP PHPDoc *SP "}"
    summary            = *CHAR ("." 1*CRLF / 2*CRLF)
    description        = 1*(CHAR / inline-tag) 1*CRLF ; any amount of characters
                                                     ; with inline tags inside
    tags               = *(tag 1*CRLF)
    inline-tag         = "{" tag "}"
    tag                = "@" tag-name [":" tag-specialization] [tag-details]
    tag-name           = (ALPHA / "\") *(ALPHA / DIGIT / "\" / "-" / "_")
    tag-specialization = 1*(ALPHA / DIGIT / "-")
    tag-details        = *SP (SP tag-description / tag-signature / inline-phpdoc)
    tag-description    = 1*(CHAR / CRLF)
    tag-signature      = "(" *tag-argument ")"
    tag-argument       = *SP 1*CHAR [","] *SP

Examples of use are included in chapter 5.4.

### 5.1. Summary

A Summary MUST contain an abstract of the "Structural Element" defining the
purpose. It is RECOMMENDED for Summaries to span a single line or at most two
but not more than that.

A Summary MUST end with either

* a full stop (.) followed by a line break
* or two sequential line breaks.

If a Description is provided, then it MUST be preceded by a Summary. Otherwise
the Description will be considered the Summary, until the end of the Summary
is reached.

Because a Summary is comparable to a chapter title it is beneficial to use as 
little formatting as possible. As such, contrary to the Description (see next 
chapter), no recommendation is done to support a mark-up language. It is 
explicitly left up to the implementing application whether it wants to support 
this or not.

### 5.2. Description

The Description is OPTIONAL but SHOULD be included when the
"Structural Element", which this DocBlock precedes, contains more operations, or
more complex operations, than can be described in the Summary alone.

Any application parsing the Description is RECOMMENDED to support the
Markdown mark-up language for this field so that it is possible for the author
to provide formatting and a clear way of representing code examples.

Common uses for the Description are (amongst others):

* To provide more detail than the Summary on what this method does.
* To specify of what child elements an input or output array, or object, is
  composed.
* To provide a set of common use cases or scenarios in which the
  "Structural Element" may be applied.

### 5.3. Tags

Tags provide a way for authors to supply concise meta-data regarding the
succeeding "Structural Element". Each tag starts on a new line, followed
by an at-sign (@) and a tag-name followed by white-space and meta-data
(including a description) or Inline PHPDoc.

If meta-data is provided, it MAY span multiple lines and COULD follow a
strict format, and as such provide parameters, as dictated by the type of tag.
The type of the tag can be derived from its name.

For example:

> `@param string $argument1 This is a parameter.`
>
> The above tag consists of a name ('param') and meta-data
> ('string $argument1 This is a parameter.') where the meta-data is split into a
> "Type" ('string'), variable name ('$argument') and description
> ('This is a parameter.').

The description of a tag MUST support Markdown as a formatting language. Due to
the nature of Markdown it is legal to start the description of the tag on the
same or the subsequent line and interpret it in the same way.

So the following tags are semantically identical:

    /**
     * @var string This is a description.
     * @var string This is a
     *    description.
     * @var string
     *    This is a description.
     */

A variation of this is where, instead of a description, a tag signature is used;
in most cases the tag will in fact be an "Annotation". The tag signature is
able to provide the annotation with parameters regarding its operation.

If a tag signature is present then there MUST NOT be a description present in
the same tag.

The meta-data supplied by tags could result in a change of actual runtime
behavior of the succeeding "Structural Element", in which case the term
"Annotation" is commonly used instead of "Tag".

Annotations will not be described in further detail in this specification as
this falls beyond scope. This specification provides a basis on top of which
annotations may be implemented.

#### 5.3.1. Tag Name

Tag names indicate what type of information is represented by this tag, or in
case of annotations which behaviour must be injected into the succeeding
"Structural Element".

In support of annotations, it is allowable to introduce a set of tags designed
specifically for an individual application or subset of applications (and thus
not covered by this specification).

These tags, or annotations, MUST provide a namespace by either

* prefixing the tag name with a PHP-style namespace, or by
* prefixing the tag name with a single vendor-name followed by a hyphen.

Example of a tag name prefixed with a php-style namespace (the prefixing slash
is OPTIONAL):

```php
@\Doctrine\Orm\Mapping\Entity()
```

> *Note*: The PHPDoc Standard DOES NOT make assumptions on the meaning of a tag
> unless specified in this document or subsequent additions or extensions.
>
> This means that you CAN use namespace aliases as long as a prefixing namespace
> element is provided. Thus the following is legal as well:
>
>     @Mapping\Entity()
>
> Your own library or application may check for namespace aliases and make a
> FQCN from this; this has no impact on this standard.

> *Important*: Tools using the PHPDoc Standard MAY interpret namespaces that are
> registered with that application and apply custom behaviour.

Example of a tag name prefixed with a vendor name and hyphen:

```php
@phpdoc-event transformer.transform.pre
```

Tag names that are not prefixed with a vendor or namespace MUST be described in
this specification (see chapter 7) and/or any official addendum.

#### 5.3.2. Tag Specialization

In order to provide a method by which to provide nuance to the tags defined in
this standard but without expanding the base set, a tag specialization MAY be
provided after the tag name by adding a colon followed by a string that provides
a more nuanced description of the tag. The list of supported tag specializations
is not maintained in this document as it may change over time. The meta document
will contain a series of recommendations on a per-tag name basis, but projects
are free to choose their own tag specializations if applicable.

*Important*: Tools using the PHPDoc Standard MAY interpret tag specializations
that are registered with/understood by that application and apply custom
behaviour, but are only expected to implement the preceding tag name as defined
in this standard.

For example:

> `@see:unit-test \Mapping\EntityTest::testGetId`
>
> The above tag consists of a name ('see') and tag specialization ('unit-test'),
> and thus defines a relation to the unit test for the proceeding method.

#### 5.3.3. Tag Signature

Tag signatures are commonly used for annotations to supply additional meta-data
specific to the current tag.

The supplied meta-data can influence the behavior of the owning annotation and
as such influence the behavior of the succeeding "Structural Element".

The contents of a signature are to be determined by the tag type (as described
in the tag-name) and fall beyond the scope of this specification. However, a
tag-signature MUST NOT be followed by a description or other form of meta-data.

### 5.4. Inline PHPDoc

Specific Tags MAY have an "Inline PHPDoc" section at the end of the "Tag"
definition. An "Inline PHPDoc" is a "PHPDoc" element enclosed in braces and is
only present at the end of a "Tag" sequence, unless specified otherwise in a
"Tag" definition. The "Inline PHPDoc" element MUST replace any description that
COULD have been provided.

An example is the `@method` tag. This tag can be augmented using an
"Inline PHPDoc" to provide additional information regarding the parameters,
return value or any other tag supported by functions and methods.

An example of this is:

```php
/**
 * @method int MyMagicMethod(string $argument1) {
 *     This is the summary for MyMagicMethod.
 *
 *     @param string $argument1 Description for argument 1.
 *
 *     @return int
 * }
 */
class MyMagicClass
{
    ...
}
```

In this example is described how the `@method` tag for the Magic Method
`MyMagicMethod` has a complete PHPDoc definition associated with it. In this
definition all constraints, constructs and tags that apply to a normal usage of
PHPDoc also apply.

The meaning of an "Inline PHPDoc" element differs based on the context in which
it is provided. In the example above the "Inline PHPDoc" provides a regular
PHPDoc definition as would precede a method.

To prevent confusion regarding the function of "Inline PHPDoc" elements MUST
their usage be restricted to tags and locations that are documented.

### 5.5. Examples

The following examples serve to illustrate the basic use of DocBlocks; it is
advised to read through the list of tags in chapter 7.

A complete example could look like the following example:

```php
/**
 * This is a Summary.
 *
 * This is a Description. It may span multiple lines
 * or contain 'code' examples using the _Markdown_ markup
 * language.
 *
 * @see Markdown
 *
 * @param int        $parameter1 A parameter description.
 * @param \Exception $e          Another parameter description.
 *
 * @\Doctrine\Orm\Mapper\Entity()
 *
 * @return string
 */
function test($parameter1, $e)
{
    ...
}
```

It is also allowed to omit the Description:

```php
/**
 * This is a Summary.
 *
 * @see Markdown
 *
 * @param int        $parameter1 A parameter description.
 * @param \Exception $parameter2 Another parameter description.
 *
 * @\Doctrine\Orm\Mapper\Entity()
 *
 * @return string
 */
function test($parameter1, $parameter2)
{
}
```

Or even omit the tags section as well (though in the following example is not
encouraged as you are missing information on the parameters and return value):

```php
/**
 * This is a Summary.
 */
function test($parameter1, $parameter2)
{
}
```

A DocBlock may also span a single line as shown in the following example.

```php
/** @var \ArrayObject $array */
public $array = null;
```

Some tags may even feature an "Inline PHPDoc" as shown in the following example.

```php
/**
 * @method int MyMagicMethod(string $argument1) {
 *     This is the summary for MyMagicMethod.
 *
 *     @param string $argument1 Description for argument 1.
 *
 *     @return int
 * }
 */
class MyMagicClass
{
    ...
}
```

## 6. Inheritance

A PHPDoc that is associated with a "Structural Element" that implements, extends 
or overrides a "Structural Element" has the ability to inherit parts of 
information from the PHPDoc associated with the "Structural Element" that is 
implemented, extended or overridden.

The PHPDoc for every type of "Structural Element" MUST inherit the following 
parts if that part is absent:

* [Summary](#51-summary)
* [Description](#52-description) and
* A specific subset of [tags](#53-tags):
  * [@version](#725-version)
  * [@author](#72-author)
  * [@copyright](#74-copyright)

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

## 6.1. Making inheritance explicit using the @inheritDoc tag

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

## 6.2. Using the {@inheritDoc} inline tag to augment a Description 

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

### 6.3. Class Or Interface

In addition to the inherited descriptions and tags as defined in this chapter's
root, a class or interface MUST inherit the following tags:

* [@package](#712-package)

A class or interface SHOULD inherit the following deprecated tags if supplied:

* [@subpackage](#719-subpackage-deprecated)

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

### 6.4. Method

In addition to the inherited descriptions and tags as defined in this chapter's
root, a function or method in a class or interface MUST inherit the following tags:

* [@param](#713-param)
* [@return](#715-return)
* [@throws](#720-throws)

### 6.5. Constant Or Property

In addition to the inherited descriptions and tags as defined in this chapter's
root, a constant or property in a class MUST inherit the following tags:

* [@var](#722-type)

## 7. Tags

Unless specifically mentioned in the description each tag MAY occur zero or more
times in each "DocBlock".

### 7.1.. @api

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

### 7.2. @author

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

### 7.3. @category [deprecated]

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

### 7.4. @copyright

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

### 7.5. @deprecated

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

### 7.6. @example

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

### 7.7. @global

TODO: The definition of this item should be discussed and whether it may or
may not be superceded in part or in whole by the @var tag.

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

### 7.8. @internal

The @internal tag is used to denote that the associated "Structural Element" is
a structure internal to this application or library. It may also be used inside
a description to insert a piece of text that is only applicable for
the developers of this software.

#### Syntax

    @internal

or inline:

    {@internal [description]}}

The inline version of this tag may, contrary to other inline tags, contain
text but also other inline tags. To increase readability and ease parsing
the tag should be terminated with a double closing brace, instead of a single
one.

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
 * {@internal Silently adds one extra Foo to compensate for lack of Foo }}
 *
 * @return int Indicates the number of items.
 */
function count()
{
    <...>
}
```

### 7.9. @license

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
 * @license GPL-2.0+
 *
 * @license http://www.spdx.org/licenses/MIT MIT License
 */
```

### 7.10. @link [deprecated]

*This tag is deprecated in favor of the `@see` tag, which since this
specification may relate to URIs.*

The @link tag indicates a custom relation between the associated
"Structural Element" and a website, which is identified by an absolute URI.

#### Syntax

    @link [URI] [description]

or inline

    @link [URI] [description]

#### Description

The @link tag can be used to define a relation, or link, between the
"Structural Element", or part of the description when used inline, to an URI.

The URI MUST be complete and welformed as specified in [RFC 2396][RFC2396].

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

### 7.11. @method

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

### 7.12. @package

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

### 7.13. @param

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

### 7.14. @property

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

### 7.15. @return

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

### 7.16. @see

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

### 7.17. @since

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

### 7.18. @subpackage [deprecated]

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

### 7.19. @throws

The @throws tag is used to indicate whether "Structural Elements" throw a
specific type of exception.

#### Syntax

    @throws ["Type"] [<description>]

#### Description

The @throws tag MAY be used to indicate that "Structural Elements" throw a
specific type of error.

The type provided with this tag MUST represent an object of the class Exception
or any subclass thereof.

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

### 7.20. @todo

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

### 7.21. @uses

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

### 7.22. @var

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

### 7.23. @version

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

## Appendix A. Types

### ABNF

A Type has the following [ABNF][RFC5234] definition:

    type-expression  = type *("|" type)
    type             = class-name / keyword / array
    array            = (type / array-expression) "[]" / generic
    array-expression = "(" type-expression ")"
    generic          = collection-type "<" [type-expression "," *SP] type-expression ">"
    collection-type  = class-name / "array"
    class-name       = ["\"] label *("\" label)
    label            = (ALPHA / %x7F-FF) *(ALPHA / DIGIT / %x7F-FF)
    keyword          = "array" / "bool" / "callable" / "false" / "float" / "int" / "mixed" / "null" / "object" /
    keyword          = "resource" / "self" / "static" / "string" / "true" / "void" / "$this"

### Details

When a "Type" is used the user will expect a value, or set of values, as detailed below.

When the "Type" consists of multiple types then these MUST be separated with the vertical bar sign (|). Any
interpreter supporting this specification MUST recognize this and split the "Type" before evaluating.

For example: `@return int|null`

#### Arrays

The value represented by "Type" can be an array. The type MUST be defined following the format of one of the
following options:

1. unspecified, no definition of the contents of the represented array is given.
   Example: `@return array`

2. specified containing a single type, the Type definition informs the reader of the type of each array value. Only one
   type is then expected for each value in a given array.

   Example: `@return int[]`

   Please note that _mixed_ is also a single type and with this keyword it is possible to indicate that each array
   value contains any possible type.

3. specified as containing multiple types, the Type definition informs the reader of the type of each array value.
   Each value can be of any of the given types.
   Example: `@return (int|string)[]`

4. specified using the Generics notation, see the next chapter "Collections" for a description on this notation.

#### Collections

The value represented by "Type" can also be a [Collection][COLLECTION], a class that contains a list of keys with
values. Collections can be denoted using a format derived from Generics in Java; as such aptly named Generics-style
notation.

With Generics-style notation it is REQUIRED to specify a class name, or the array keyword, followed by the type of
the values enclosed with angular brackets.

Example: to indicate that this element returns an object of class ArrayObject that only contains a series of strings.

    @return \ArrayObject<string>

The type of the values in a Collection MAY be another array and even another Collection,

    @return \ArrayObject<\ArrayObject<int>>

A Collection MAY optionally define the type of the keys of said Collection by adding an additional type definition
between the angular brackets before the identifier denoting the values' type. These two should be separated by a comma.

Example: to declare an ArrayObject collection containing a list of strings with integer keys.

    @return \ArrayObject<int, string>

The type of a value, or key, MAY consist of several different types, this can be represented by separating each
individual type with a vertical bar sign between the angular brackets.

    @return \ArrayObject<string|bool>

### Valid Class Name

A valid class name seen from the context where this type is mentioned. Thus
this may be either a Fully Qualified Class Name (FQCN) or if present in a
namespace a local name.

The element to which this type applies is either an instance of this class
or an instance of a class that is a (sub-)child to the given class.

> Due to the above nature it is RECOMMENDED for applications that
> collect and shape this information to show a list of child classes
> with each representation of the class. This would make it obvious
> for the user which classes are acceptable as type.

### Keyword

A keyword defining the purpose of this type. Not every element is determined by a class but still worthy of
classification to assist the developer in understanding the code covered by the DocBlock.

**Note:**
> Most of these keywords are allowed as class names in PHP and as such are hard to distinguish from real classes. As
> such the keywords MUST be lowercase, as most class names start with an uppercase first character, and you SHOULD NOT
> use classes with these names in your code.

> There are more reasons to not name classes with the names of these keywords but that falls beyond the scope of this
> specification.

The following keywords are recognized by this PSR:

1.  `string`, the element to which this type applies is a string of binary characters.

2.  `int`, the element to which this type applies is a whole number or integer.

3.  `bool`, the element to which this type applies only has state `TRUE` or `FALSE`.

4.  `float`, the element to which this type applies is a continuous, or real, number.

5.  `object`, the element to which this type applies is the instance of an undetermined class.

6.  `mixed`, the element to which this type applies can be of any type as specified here. It is not known on compile
    time which type will be used.

7.  `array`, the element to which this type applies is an array of values.

8.  `resource`, the element to which this type applies is a resource per the [definition of PHP][PHP_RESOURCE].

9.  `void`, this type is commonly only used when defining the return type of a method or function.

    The basic definition is that the element indicated with this type does not contain a value and the user should not
    rely on any retrieved value.

    **Example 1:**
    ```php
    /**
     * @return void
     */
    function outputHello()
    {
        echo 'Hello world';
    }
    ```

    In the example above no return statement is specified and thus the return value is not determined.

    **Example 2:**
    ```php
    /**
     * @param bool $hi when true 'Hello world' is echo-ed.
     *
     * @return void
     */
    function outputHello($quiet)
    {
        if ($quiet} {
            return;
        }
        echo 'Hello world';
    }
    ```

    In this example the function contains a return statement without a given value. Because there is no actual value
    specified, this also qualifies as type `void`.

10. `null`, the element to which this type applies is a `NULL` value or, in technical terms, does not exist.

    A big difference compared to `void` is that this type is used in any situation where the described element may at
    any given time contain an explicit `NULL` value.

    **Example 1:**
    ```php
    /**
     * @return null
     */
    function foo()
    {
        echo 'Hello world';
        return null;
    }
    ```

    This type is commonly used in conjunction with another type to indicate that it is possible that nothing is
    returned.

    **Example 2:**
    ```php
    /**
     * @param bool $create_new When true returns a new stdClass.
     *
     * @return stdClass|null
     */
    function foo($create_new)
    {
        if ($create_new) {
            return new stdClass();
        }
        return null;
    }
    ```

11. `callable`, the element to which this type applies is a pointer to a function call. This may be any type of callable
    as defined in the PHP manual about [pseudo-types][PHP_PSEUDO] or the section on [callable][PHP_CALLABLE].

12. `false` or `true`, the element to which this type applies will have the value `TRUE` or `FALSE`. No other value will
    be returned from this element.

13. `self`, the element to which this type applies is of the same class as which the documented element is originally
    contained.

    **Example:**

    > Method *c* is contained in class *A*. The DocBlock states that its return value is of type `self`. As such method
    > *c* returns an instance of class *A*.

    This may lead to confusing situations when inheritance is involved.

    **Example (previous example situation still applies):**

    > Class *B* extends class *A* and does not redefine method *c*. As such it is possible to invoke method *c* from
    > class *B*.

    In this situation ambiguity may arise as `self` could be interpreted as either class *A* or *B*. In these cases
    `self` MUST be interpreted as being an instance of the class where the DocBlock containing the `self` type is
    written.

    In the examples above `self` MUST always refer to class *A*, since it is defined with method *c* in class *A*.

    > Due to the above nature it is RECOMMENDED for applications that collect and shape this information to show a list
    > of child classes with each representation of the class. This would make it obvious for the user which classes are
    > acceptable as type.

14. `static`, the element to which this type applies is of the same class as which the documented element is contained,
    or when encountered in a subclass is of type of that subclass instead of the original class.

    This keyword behaves the same way as the [keyword for late static binding][PHP_OOP5LSB] (not the static method,
    property, nor variable modifier) as defined by PHP.

15. `$this`, the element to which this type applies is the same exact instance as the current class in the given
    context. As such this type is a stricter version of `static` as, in addition, the returned instance must not only be
    of the same class but also the same instance.

    This type is often used as return value for methods implementing the [Fluent Interface][FLUENT] design pattern.

[RFC2119]:      https://tools.ietf.org/html/rfc2119
[RFC5234]:      https://tools.ietf.org/html/rfc5234
[RFC2396]:      https://tools.ietf.org/html/rfc2396
[SEMVER2]:      http://www.semver.org
[PHP_SUBSTR]:   https://php.net/manual/function.substr.php
[PHP_RESOURCE]: https://php.net/manual/language.types.resource.php
[PHP_PSEUDO]:   https://php.net/manual/language.pseudo-types.php
[PHP_CALLABLE]: https://php.net/manual/language.types.callable.php
[PHP_OOP5LSB]:  https://php.net/manual/language.oop5.late-static-bindings.php
[SPDX]:         https://www.spdx.org/licenses
[DEFACTO]:      http://www.phpdoc.org/docs/latest/index.html
[PHPDOC.ORG]:   http://www.phpdoc.org/
[FLUENT]:       https://en.wikipedia.org/wiki/Fluent_interface
[COLLECTION]:   https://en.wikipedia.org/wiki/Collection_(abstract_data_type)
