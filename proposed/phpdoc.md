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
  - [5.4. Examples](#54-examples)
- [Appendix A. Types](#appendix-a-types)
  - [ABNF](#abnf)
  - [Details](#details)
  - [Valid Class Name](#valid-class-name)
  - [Keyword](#keyword)

## 1. Introduction

The purpose of the PSR is to provide a formal definition of the PHPDoc standard.

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
  > syntax as described in this specification (such as the description and tags).

* "Structural Element" is a collection of Programming Constructs which MAY be
  preceded by a DocBlock. The collection contains the following constructs:

  * require(\_once)
  * include(\_once)
  * class
  * interface
  * trait
  * function (including methods)
  * property
  * constant
  * variables, both local and global scope.

  It is RECOMMENDED to precede a "Structural Element" with a DocBlock where it is
  defined and not with each usage. It is common practice to have the DocBlock
  precede a Structural Element but it MAY also be separated by an undetermined
  number of empty lines.

  Example:

  ```php
  /**
   * This is a counter.
   * @var int $int
   */
  $int = 0;

  /** @var int $int This is a counter. */
  $int = 0;

  /* comment block... this is not a docblock */
  $int++;

  // single line comment... this is not a docblock
  $int++;
  ```

  or

  ```php
  /**
   * This class acts as an example on where to position a DocBlock.
   */
  class Foo
  {
      /** @var string|null $title contains a title for the Foo */
      protected $title = null;

      /**
       * Sets a single-line title.
       *
       * @param string $title A text for the title.
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

  It is NOT RECOMMENDED to use compound definitions for Constants or Properties, since the
  handling of DocBlocks in these situations can lead to unexpected results. If compound statement is
  used each element SHOULD have a preceding DocBlock.

  Example:

  ```php
    class Foo
    {
      protected
        /**
         * @var string Should contain a name
         */
        $name,
        /**
         * @var string Should contain a description
         */
        $description;
    }
  ```

  An example of use that falls beyond the scope of this Standard is to document
  the variable in a foreach explicitly; several IDEs use this information to
  assist their auto-completion functionality.

* "DocComment" is a special type of comment which MUST

  - start with the character sequence `/**` followed by a whitespace character
  - end with `*/` and
  - have zero or more lines in between.

  In the case where a DocComment spans multiple lines, every line MUST start with
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

* "Type" is the determination of what type of data is associated with an element.
  This is commonly used when determining the exact values of arguments, constants,
  properties and more.

  See Appendix A for more detailed information about types.

* "FQSEN" is an abbreviation for Fully Qualified Structural Element Name. This
  notation expands on the Fully Qualified Class Name and adds a notation to
  identify class/interface/trait members and re-apply the principles of the FQCN
  to Interfaces, Traits, Functions and global Constants.

  The following notations can be used per type of "Structural Element":

  - *Namespace*:      `\My\Space`
  - *Function*:       `\My\Space\myFunction()`
  - *Constant*:       `\My\Space\MY_CONSTANT`
  - *Class*:          `\My\Space\MyClass`
  - *Interface*:      `\My\Space\MyInterface`
  - *Trait*:          `\My\Space\MyTrait`
  - *Method*:         `\My\Space\MyClass::myMethod()`
  - *Property*:       `\My\Space\MyClass::$my_property`
  - *Class Constant*: `\My\Space\MyClass::MY_CONSTANT`

  A FQSEN has the following [ABNF][RFC5234]
  definition:

      FQSEN    = fqnn / fqcn / constant / method / property  / function
      fqnn     = "\" [name] *("\" [name])
      fqcn     = fqnn "\" name
      constant = ((fqnn "\") / (fqcn "::")) name
      method   = fqcn "::" name "()"
      property = fqcn "::$" name
      function = fqnn "\" name "()"
      name     = (ALPHA / "_") *(ALPHA / DIGIT / "_")

## 4. Basic Principles

* A PHPDoc MUST always be contained in a "DocComment"; the combination of these
  two is called a "DocBlock".

* A DocBlock MUST directly precede a "Structural Element"

## 5. The PHPDoc Format

The PHPDoc format has the following [ABNF][RFC5234]
definition:

    PHPDoc             = [summary [description]] [tags]
    eol                = [CR] LF ; to compatible with PSR-12
    summary            = 1*CHAR 2*eol
    description        = 1*(CHAR / inline-tag) 1*eol ; any amount of characters
                                                     ; with inline tags inside
    tags               = *(tag 1*eol)
    inline-tag         = "{" tag "}"
    tag                = "@" tag-name [":" tag-specialization] [tag-details]
    tag-name           = (ALPHA / "\") *(ALPHA / DIGIT / "\" / "-" / "_")
    tag-specialization = 1*(ALPHA / DIGIT / "-")
    tag-details        = (1*SP tag-description)
    tag-description    = (CHAR / inline-tag) *(CHAR / inline-tag / eol)
    tag-argument       = *SP 1*CHAR [","] *SP

Examples of use are included in chapter 5.4.

### 5.1. Summary

A Summary MUST contain an abstract of the "Structural Element" defining the
purpose. It is RECOMMENDED for Summaries to span a single line or at most two,
but not more than that.

A Summary MUST end with two sequential line breaks, unless it is the only content
in the PHPDoc.

If a Description is provided, then it MUST be preceded by a Summary. Otherwise
the Description will be considered the Summary, until the end of the Summary
is reached.

Because a Summary is comparable to a chapter title, it is beneficial to use as
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
by an at-sign (@) and a tag-name, followed by white-space and meta-data
(including a description).

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
the nature of Markdown, it is legal to start the description of the tag on the
same or the subsequent line and interpret it in the same way.

Thus, the following tags are semantically identical:

    /**
     * @var string This is a description.
     * @var string This is a
     *    description.
     * @var string
     *    This is a description.
     */
```

This definition does NOT apply to _Annotation_ tags, which are not in scope.

#### 5.3.1. Tag Name

Tag names indicate what type of information is represented by this tag.

### 5.4. Examples

The following examples serve to illustrate the basic use of DocBlocks; it is
advised to read through the list of tags in the [Tag Catalog PSR][TAG_PSR].

A complete example could look like this:

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

Or even omit the tags section as well (though it is not encouraged,
as you are missing information on the parameters and return value):

```php
/**
 * This is a Summary.
 */
function test($parameter1, $parameter2)
{
}
```

A DocBlock may also span a single line:

```php
/** @var \ArrayObject $array */
public $array = null;
```

## Appendix A. Types

### ABNF

A Type has the following [ABNF][RFC5234] definition:

    type-expression  = type *("|" type) *("&" type)
    type             = class-name / keyword / array
    array            = (type / array-expression) "[]"
    array-expression = "(" type-expression ")"
    class-name       = ["\"] label *("\" label)
    label            = (ALPHA / %x7F-FF) *(ALPHA / DIGIT / %x7F-FF)
    keyword          = "array" / "bool" / "callable" / "false" / "float" / "int" / "iterable" / "mixed" / "never"
    keyword          =/ "null" / "object" / "resource" / "self" / "static" / "string" / "true" / "void" / "$this"

### Details

When a "Type" is used, the user will expect a value, or set of values, as detailed below.

When the "Type" consists of multiple types, then these MUST be separated with either
the vertical bar sign (|) for union type or the ampersand (&) for intersection type.
Any interpreter supporting this specification MUST recognize this and split the "Type" before evaluating.

Union type example:
>`@return int|null`

Intersection type example:
>`@var \MyClass&\PHPUnit\Framework\MockObject\MockObject $myMockObject`

#### Arrays

The value represented by "Type" can be an array. The type MUST be defined following the format of one of the
following options:

1. unspecified: no definition of the contents of the represented array is given.
   Example: `@return array`

2. specified containing a single type: the Type definition informs the reader of the type of each array value. Only one
   type is then expected for each value in a given array.

   Example: `@return int[]`

   Please note that _mixed_ is also a single type and with this keyword it is possible to indicate that each array
   value contains any possible type.

3. specified as containing multiple types: the Type definition informs the reader of the type of each array value.
   Each value can be of any of the given types.
   Example: `@return (int|string)[]`

### Valid Class Name

A valid class name is seen based on the context where this type is mentioned. Thus
this may be either a Fully Qualified Class Name (FQCN) or a local name if present in a
namespace.

The element to which this type applies is either an instance of this class
or an instance of a class that is a (sub-)child to the given class.

> Due to the above nature, it is RECOMMENDED for applications that
> collect and shape this information to show a list of child classes
> with each representation of the class. This would make it obvious
> for the user which classes are acceptable as type.

### Keyword

A keyword defines the purpose of this type. Not every element is determined by a class but still worthy of
classification to assist the developer in understanding the code covered by the DocBlock.

**Note:**
> Most of these keywords are allowed as class names in PHP and can be hard to distinguish from real classes. As
> such, the keywords MUST be lowercase, as most class names start with an uppercase first character, and you SHOULD NOT
> use classes with these names in your code.

> There are more reasons to not name classes with the names of these keywords, but that falls beyond the scope of this
> specification.

The following keywords are recognized by this PSR:

1.  `bool`: the element to which this type applies only has state `TRUE` or `FALSE`.

2.  `int`: the element to which this type applies is a whole number or integer.

3.  `float`: the element to which this type applies is a continuous, or real, number.

4.  `string`: the element to which this type applies is a string of binary characters.

5.  `object`: the element to which this type applies is the instance of an undetermined class.

6.  `array`: the element to which this type applies is an array of values.

7.  `iterable`: the element to which this type applies is an array or Traversable object per the [definition of PHP][PHP_ITERABLE].

8.  `resource`: the element to which this type applies is a resource per the [definition of PHP][PHP_RESOURCE].

9.  `mixed`: the element to which this type applies can be of any type as specified here. It is not known at compile
    time which type will be used.

10. `void`: this type is commonly only used when defining the return type of a method or function, indicating
    "nothing is returned", and thus the user should not rely on any returned value.

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

    In the example above, no return statement is specified and thus the return value is not determined.

    **Example 2:**
    ```php
    /**
     * @param bool $quiet when true 'Hello world' is echo-ed.
     *
     * @return void
     */
    function outputHello($quiet)
    {
        if ($quiet) {
            return;
        }
        echo 'Hello world';
    }
    ```

    In this example, the function contains a return statement without a given value. Because there is no actual value
    specified, this also qualifies as type `void`.

11. `null`: the element to which this type applies is a `NULL` value or, in technical terms, does not exist.

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

12. `callable`: the element to which this type applies is a pointer to a function call. This may be any type of callable
    as per the [definition of PHP][PHP_CALLABLE].

13. `false` or `true`: the element to which this type applies will have the value `TRUE` or `FALSE`. No other value will
    be returned from this element.

14. `self`: the element to which this type applies is of the same class in which the documented element is originally
    contained.

    **Example:**

    > Method *c* is contained in class *A*. The DocBlock states that its return value is of type `self`. As such, method
    > *c* returns an instance of class *A*.

    This may lead to confusing situations when inheritance is involved.

    **Example (previous example situation still applies):**

    > Class *B* extends class *A* and does not redefine method *c*. As such, it is possible to invoke method *c* from
    > class *B*.

    In this situation, ambiguity may arise as `self` could be interpreted as either class *A* or *B*. In these cases,
    `self` MUST be interpreted as being an instance of the class where the DocBlock containing the `self` type is
    written.

    In the examples above, `self` MUST always refer to class *A*, since it is defined with method *c* in class *A*.

    > Due to the above nature, it is RECOMMENDED for applications that collect and shape this information to show a list
    > of child classes with each representation of the class. This would make it obvious for the user which classes are
    > acceptable as type.

15. `static`: the element to which this type applies is of the same class in which the documented element is contained,
    or, when encountered in a subclass, is of type of that subclass instead of the original class.

    This keyword behaves the same way as the [keyword for late static binding][PHP_OOP5LSB] (not the static method,
    property, nor variable modifier) as defined by PHP.

16. `$this`: the element to which this type applies is the same exact instance as the current class in the given
    context. As such, this type is a stricter version of `static`, because the returned instance must not only be
    of the same class but also the same instance.

    This type is often used as return value for methods implementing the [Fluent Interface][FLUENT] design pattern.

17. `never`: denotes that element isn't going to return anything and always throws exception or terminates
    the program abnormally (such as by calling the library function `exit`).

[RFC2119]:      https://tools.ietf.org/html/rfc2119
[RFC5234]:      https://tools.ietf.org/html/rfc5234
[PHP_RESOURCE]: https://php.net/manual/language.types.resource.php
[PHP_ITERABLE]: https://php.net/manual/language.types.iterable.php
[PHP_PSEUDO]:   https://php.net/manual/language.pseudo-types.php
[PHP_CALLABLE]: https://php.net/manual/language.types.callable.php
[PHP_OOP5LSB]:  https://php.net/manual/language.oop5.late-static-bindings.php
[DEFACTO]:      http://www.phpdoc.org/docs/latest/index.html
[PHPDOC.ORG]:   http://www.phpdoc.org/
[FLUENT]:       https://en.wikipedia.org/wiki/Fluent_interface
[TAG_PSR]:      TBD
