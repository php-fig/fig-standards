Introduction
============

The intent of this guide is to reduce cognitive friction when scanning code
from different authors. It does so by enumerating a shared set of rules and
expectations about how to format PHP code. This guide is derived from
commonalities among the various member projects.


Overview
--------

Please review the remainder of this guide for details on each of the following
points.

- Use only `<?php` and `<?=` opening tags for PHP code; leave out the closing
  `?>` tag when the file contains only PHP code.

- Use 4 spaces for indenting, not tabs.

- There is no hard limit on line length; the soft limit is 120 characters;
  lines of 80 characters or less are strongly encouraged. Do not add trailing
  whitespace at the end of lines. Use Unix line endings (LF).

- Namespace all classes; place one blank line after the `namespace`
  declaration, and one blank line after the block of `use` declarations.

- Declare class names in `StudlyCaps`; opening braces for classes go on the
  next line, and closing braces go on their own line.

- Declare method names in `camelCase`; opening braces for methods go on the
  next line, and closing braces go on their own line.

- Declare visibility on all properties and methods; `static` declarations come
  before the visibility declaration; `abstract` and `final` declarations come
  before `static` and visibility.
  
- Control structure keywords have one space after them; function and method
  calls do not.

- Opening braces for control structures go on the same line, and closing
  braces go on their own line.

- Opening parentheses for control structures have no space after them, and
  closing parentheses for control structures have no space before.


PHP Tags
========

Use the long `<?php ?>` tags for PHP code. Use of short-echo `<?= ?>` tags is
also allowed. Do not use the other tag variations.

In files that contain only PHP, leave out the closing `?>` tag.


Indenting and Lines
===================

Indenting
---------

Use an indent of 4 spaces. Do not use tabs. The use of spaces helps to avoid
problems with diffs, patches, history, and annotations, and provides
fine-grained sub-indentation for inter-line alignment.

Line Length
-----------

There is no hard limit on line length. The soft limit on line length is 120
characters; automated style checkers must warn but not error at the soft
limit. This guide strongly encourages lines no longer than 80 characters, and
encourages splitting longer lines into multiple subsequent lines of no more
than 80 characters each.

Line Endings
------------

Use the Unix LF (linefeed) line ending on all PHP files. Do not add trailing
spaces at the end of lines.

Blank Lines
-----------

Blank lines may be added to improve readability and to indicate related blocks
of logic.


`namespace`, `use`, and `class`
===============================

> N.b.: Formal namespaces were introduced in PHP 5.3. Code written for 5.2.x
> and before must use the pseudo-namespacing convention of `Vendor_`
> prefixes on class names. Code written for PHP 5.3 and after must use
> formal namespaces.

All namespaces and classes are to be named with PSR-0 in mind. This means each
class is in a file by itself, and is in a namespace of at least one level: a
top-level vendor name.

The `namespace` line has one blank line after it.

All `use` declarations go after the `namespace` declaration. There is one
`use` keyword per declaration.

The `use` block has one blank line after it.
    
Class names are in `StudlyCaps`. The opening and closing braces for the
class go on their own line.

The `extends` and `implements` keywords are on the same line as the class
name.

    <?php
    namespace Vendor\Package;
    
    use FooClass;
    use BarClass as Bar;
    use OtherVendor\OtherPackage\BazClass;
    
    class ClassName extends ParentClass implements InterfaceName
    {
        // constants, properties, methods
    }

Lists of `implements` may be split across multiple lines, where each
subsequent line is indented once. List only one interface per line.

    <?php
    namespace Vendor\Package;
    
    use FooClass;
    use BarClass as Bar;
    use OtherVendor\OtherPackage\BazClass;
    
    class ClassName extends ParentClass implements
        InterfaceName,
        AnotherInterfaceName,
        YetAnotherInterface,
        InterfaceInterface
    {
        // constants, properties, methods
    }


Class Constants, Properties, and Methods
========================================

Constants
---------

Class constants are all upper case with underscore separators.

    <?php
    namespace Vendor\Package;

    class ClassName
    {
        const CONSTANT_NAME = 'constant value';
    }


Properties
----------

This guide expressly avoids any recommendation regarding the use of
`$StudlyCaps`, `$camelCase`, or `$under_score` property names. It is often the
case that property names map directly to field names in external data sources.
Changing between naming conventions when changing contexts merely to suit a
style guide would be counterproductive in such cases.

Whatever naming convention is used must be applied consistently within a
reasonable scope. That scope may be vendor-level, package-level, class-level,
or method-level.

Declare visibility on all properties. Some projects prefix property names with
a single underscore to indicate protected or private visibility; this guide
discourages but does not disallow that practice.

A property declaration looks like the following.

    <?php
    namespace Vendor\Package;
    
    class ClassName
    {
        public $foo = null;
    }


Methods
-------

Declare method names in `camelCase()` with no space after the method name. The
opening and closing braces go on their own line. There is no space after the
opening parenthesis, and there is no space before the closing parenthesis.

Declare visibility on all methods. Some projects prefix method names with a
single underscore to indicate protected or private visibility; this guide
discourages but does not disallow that practice.

A method declaration looks like the following. Note the placement of
parentheses, commas, spaces, and braces:

    <?php
    namespace Vendor\Package;
    
    class ClassName
    {
        public function fooBarBaz($arg1, &$arg2, $arg3 = [])
        {
            // method body
        }
    }
    

Method Arguments
----------------

Method arguments with default values always go at the end of the argument
list.

    <?php
    namespace Vendor\Package;
    
    class ClassName
    {
        public function foo($arg1, $arg2, $arg3 = [])
        {
            // method body
        }
    }
    
Argument lists may be split across subsequent indented lines; list only one
argument per line. When the argument list is split across multiple lines, the
closing parenthesis and opening brace are placed together on their own line.

    <?php
    namespace Vendor\Package;
    
    class ClassName
    {
        public function aVeryLongMethodName(
            ClassTypeHint $arg1,
            &$arg2,
            array $arg3 = []
        ) {
            // method body
        }
    }


`static`, `abstract`, and `final`
---------------------------------

When present, the `static` declaration precedes the visibility declaration.
This aids in differentiating static properties and methods from instance
properties and methods.

When present, the `abstract` and `final` declarations precede both the
`static` and visibility declarations.

    <?php
    namespace Vendor\Package;
    
    class ClassName
    {
        static protected $foo;
        
        final static public bar()
        {
            // method body
        }
        
        abstract protected zim();
    }


Control Structures
==================

The general style rules for control structures are as follows:

- one space after the control structure keyword
- no space after the opening parenthesis
- no space before the closing parenthesis
- one space between the closing parenthesis and the opening brace
- structure body indented once
- closing brace on its own line, outdented once from the body

Always use braces to enclose the body of each structure. This standardizes how
the structures look, and reduces the likelihood of introducing errors as new
lines get added to the body.


`if`, `elseif`, `else`
----------------------

An `if` structure looks like the following. Note the placement of parentheses,
spaces, and braces; and that `else` and `elseif` are on the same line as the
closing brace from the earlier body.

    <?php
    if ($expr1) {
        // if body
    } elseif ($expr2) {
        // elseif body
    } else {
        // else body;
    }

> N.b.: There appears to be no consistency between projects, and often not
> even within the same project, on the use of `else if` vs `elseif`. This
> guide encourages the use of `elseif` so that all control structures look
> like single words.


`switch`, `case`
----------------    

A `switch` structure looks like the following. Note the placement of
parentheses, spaces, and braces; the indent levels for `case` and `break`; and
the presence of a `// no break` comment when a break is intentionally omitted.

    <?php
    switch ($expr) {
        case 1:
            echo 'First case';
        break;
        case 2:
            echo 'Second case';
            // no break
        default:
            echo 'Default case';
        break;
    }


`while`, `do while`
-------------------

A `while` statement looks like the following. Note the placement of
parentheses, spaces, and braces.

    <?php
    while ($expr) {
        // structure body
    }

Similarly, a `do while` statement looks like the following. Note the placement
of parentheses, spaces, and braces.

    <?php
    do {
        // structure body;
    } while ($expr);


`for`
-----

A `for` statement looks like the following. Note the placement of parentheses,
spaces, and braces.

    <?php
    for ($i = 0; $i < 10; $i++) {
        // for body
    }
    

`foreach`
---------
    
A `foreach` statement looks like the following. Note the placement of
parentheses, spaces, and braces.

    <?php
    foreach ($iterable as $key => $value) {
        // foreach body
    }


`try`, `catch`
--------------

A `try catch` block looks like the following. Note the placement of
parentheses, spaces, and braces.

    <?php
    try {
        // try body
    } catch (FirstExceptionType $e) {
        // catch body
    } catch (OtherExceptionType $e) {
        // catch body
    }


Conclusion
==========

There are many points of style and practice intentionally omitted by this
guide. Future recommendations may extend and revise the guide.
