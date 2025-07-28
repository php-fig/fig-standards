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
    - [5.2.1 Inline tags](#521-inline-tags)
  - [5.3. Tags](#53-tags)
    - [5.3.1. Tag Specialization](#531-tag-specialization) 
    - [5.3.2. Tag Name](#532-tag-name)
  - [5.4 whitespace handling](#54-whitespace-handling)
  - [5.5. Examples](#55-examples)

## 1. Introduction

This PSR defines a formal specification for the PHPDoc standard.

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
  > syntax as described in this specification (such as the Description and tags).

* "Structural Element" is a collection of programming constructs which MAY be
  preceded by a DocBlock. The collection contains the following constructs:

  * `require` / `include` (and their `\_once` variants)
  * `class` / `interface` / `trait` / `enum`
  * `function` (both standalone functions and class methods)
  * variables (local and global scope) and class properties
  * constants (global constants via `define` and class constants)
  * cases 

  It is RECOMMENDED to precede a "Structural Element" with a DocBlock where it
  is defined. It is common practice to have the DocBlock precede a Structural
  Element, but it MAY also be separated by any number of empty lines.

  Examples:

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

  ```php
  /**
   * This class shows an example on where to position a DocBlock.
   */
  class Foo
  {
      /** @var ?string $title contains a title for the Foo */
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

  It is NOT RECOMMENDED to use compound definitions for constants or properties,
  since the handling of DocBlocks in these situations can lead to unexpected
  results. If a compound statement is used, each element SHOULD have a preceding
  DocBlock.

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

* "DocComment" is a special type of comment that MUST

  - start with the character sequence `/**` followed by a whitespace character
  - end with `*/` and
  - have zero or more lines in between.

  When a DocComment spans multiple lines, every line MUST start with an asterisk
  (`*`) that SHOULD be aligned with the first asterisk of the opening clause.

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

* "Tag" is a single piece of meta information regarding a "Structural Element".

## 4. Basic Principles

* A PHPDoc MUST always be contained in a "DocComment"; the combination of these
  two is called a "DocBlock".

* A DocBlock MUST directly precede a "Structural Element".

## 5. The PHPDoc Format

The PHPDoc format has the following [ABNF][RFC5234] definition:

    PHPDoc             = [summary [description]] [tags]
    eol                = [CR] LF ; to be compatible with PSR-12
    summary            = 1*CHAR 2*eol
    description        = 1*(CHAR / inline-tag) 1*eol ; any amount of characters
                                                     ; with inline tags inside
    tags               = *(tag 1*eol)
    inline-tag         = "{" tag "}"
    tag                = "@"[tag-specialization "-"] tag-name [tag-details]
    tag-name           = (ALPHA / "\") *(ALPHA / DIGIT / "\" / "-" / "_")
    tag-specialization = 1*(ALPHA / DIGIT)
    tag-details        = (1*SP tag-description)
    tag-description    = (CHAR / inline-tag) *(CHAR / inline-tag / eol)
    tag-argument       = *SP 1*CHAR [","] *SP

Examples of use are included in chapter 5.4.

### 5.1. Summary

A Summary MUST provide an abstract of the "Structural Element" defining the
purpose. It is RECOMMENDED for Summaries to span a single line or two, but not
more than that.

A Summary MUST end with two sequential line breaks, unless it is the only
content in the PHPDoc.

If a Description is provided, then it MUST be preceded by a Summary. Otherwise
the Description risks being mistaken as the Summary.

Because a Summary is comparable to a chapter title, it is NOT RECOMMENDED to use formatting.
Contrary to the Description, no recommendation is made to support Markdown.

### 5.2. Description

The Description is OPTIONAL but SHOULD be included when the "Structural Element"
contains more complexity than can be described by the Summary alone.

Any application parsing the Description is RECOMMENDED to support the
Markdown language, to make it possible for the author to provide
formatting and a clear way of representing code examples.

Common uses for the Description:
* To provide more detail on what this method does than the Summary can do
* To specify of what child elements an array / object is composed
* To provide a set of common use cases or scenarios in which the "Structural
  Element" may be applied

#### 5.2.1 Inline tags

A description MAY contain inline tags. Inline tags MUST follow the specification of
tags with the same syntax. An inline tag MUST start with `{@` and MUST with `}`

```php
  /** 
   * Summary.
   * 
   * This is a description with an inline tag {@tag rest of the tag format}
   */
```

Inline tags MAY only represent regular tags and SHALL NOT represent _Annotation_ tags.

As inline tags are always closed with a `}` developers cannot use this char in a 
description. To overcome this issue parsers MUST support escape sequence of `{` followed by `}` MUST be interpreted as a literal closing brace.

```php
   /**
    * Summary.
    *
    * This is a description with an inline tag {@tag show case {} escape of ending char}
    */
```

### 5.3. Tags

Tags supply concise metadata for a "Structural Element". Each tag MUST start on a
new line, followed by an at-sign (`@`) and a tag-name, then optional whitespace and metadata.

If metadata is provided, it MAY span multiple lines and COULD follow a strict
format, as dictated by the specific tag.

> `@param string $argument1 This is a parameter.`
>
> The above tag consists of a _name_ (`param`) and metadata
> ('string $argument1 This is a parameter.'), where the metadata is split into a
> _type_ (`string`), variable name (`$argument1`),  and description (`This is a
> parameter.`).

The description MUST support Markdown as a formatting language. The
description of the tag MAY start on the same line or next line. The following
tags are semantically identical:

```php
    /**
     * @var string This is a description.
     * @var string This is a
     *    description.
     * @var string
     *    This is a description.
     */
```

This definition does NOT apply to _Annotation_ tags, which are not in scope.

#### 5.3.1 Tag Specialization

Tag specialization defines a scope for the tag. It is RECOMMENDED to follow the
list of tags in the tag [Tag Catalog PSR][TAG_PSR]. But the metadata of specialized
tags MAY differ from the list.

```php
  /**
   * @vendor-tag This is a description
   */
```

Parsers MAY ignore specialized tags when they are in a supported format.

#### 5.3.2. Tag Name

Tag names indicate what type of information is represented by this tag.

### 5.4. Whitespace Handling

Whitespace handling for parsing DocBlocks MUST follow these rules:

- Leading and trailing whitespace characters (spaces, tabs) on each line MUST be ignored.
- Lines in descriptions or tag metadata MAY contain multiple whitespace characters between words, but varying amounts of whitespace MUST NOT alter the meaning.
  For example: `@param string $var` is semantically equivalent to `@param     string         $var`

- A description MAY contain empty lines. These are preserved as part of the Markdown block and MUST NOT be interpreted as ending the description.
- A description ends when:
    - The DocBlock ends, or
    - A new tag starts (i.e., a line beginning with @)

In tag metadata, whitespace (space or tab) MAY delimit metadata components (e.g., type, variable name, description), but excessive whitespace MUST NOT change the meaning.

### 5.5. Examples

The following examples serve to illustrate the basic use of DocBlocks; it is
advised to read through the list of tags in the [Tag Catalog PSR][TAG_PSR].

A complete example could look like this:

```php
/**
 * This is a Summary.
 *
 * This is a Description. It may span multiple lines
 * or contain `code` examples using the _Markdown_ markup
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

The Description MAY be omitted:

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

Tags MAY also be omitted:

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
/** @var \ArrayObject $array An array of things. */
public $array = null;
```

[RFC2119]:      https://tools.ietf.org/html/rfc2119
[RFC5234]:      https://tools.ietf.org/html/rfc5234
[PHP_RESOURCE]: https://php.net/manual/language.types.resource.php
[PHP_ITERABLE]: https://php.net/manual/language.types.iterable.php
[PHP_PSEUDO]:   https://php.net/manual/language.pseudo-types.php
[PHP_CALLABLE]: https://php.net/manual/language.types.callable.php
[PHP_OOP5LSB]:  https://php.net/manual/language.oop5.late-static-bindings.php
[DEFACTO]:      http://www.phpdoc.org/docs/latest/index.html
[PHPDOC.ORG]:   http://www.phpdoc.org/
[TAG_PSR]:      TBD
