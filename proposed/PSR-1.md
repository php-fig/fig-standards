PSR-1 Coding Style Guide
========================

The intent of this guide is to reduce cognitive friction when scanning code
from different authors. It does so by enumerating a shared set of rules and
expectations about how to format PHP code.  

This guide is derived from commonalities among the various member projects. If
one project has its own style guide, that's fine for that project. But when
various authors collaborate across multiple projects, it helps to have one set
of guidelines to be used among all those projects. Thus, the benefit of this
guide is not in the rules themselves, but in the sharing of those rules.


Overview
--------

Please review the remainder of this guide for details on each of the following
points.

- Use only `<?php` and `<?=` opening tags for PHP code; leave out the closing
  `?>` tag when the file contains only PHP code.

- Use 4 spaces for indenting, not tabs.

- There is no hard limit on line length; the soft limit is 120 characters;
  lines of 80 characters or less are encouraged. Do not add trailing
  whitespace at the end of lines. Use Unix line endings (LF).

- Namespace all classes; place one blank line after the `namespace`
  declaration, and one blank line after the block of `use` declarations.

- Declare class names in `StudlyCaps`; opening braces for classes go on the
  next line, and closing braces go on their own line.

- Declare method names in `camelCase`; opening braces for methods go on the
  next line, and closing braces go on their own line.

- Declare visibility on all properties and methods; declare `abstract` and
  `final` before the visibility, and declare `static` after the visibility.
  
- Control structure keywords have one space after them; function and method
  calls do not.

- Opening braces for control structures go on the same line, and closing
  braces go on their own line.

- Opening parentheses for control structures have no space after them, and
  closing parentheses for control structures have no space before.


General
-------

### PHP Tags

Use the long `<?php ?>` tags for PHP code. Use of short-echo `<?= ?>` tags is
also allowed. Do not use the other tag variations.

In files that contain only PHP, leave out the closing `?>` tag.


### Character Encoding

Use only UTF-8 (no BOM) for PHP code. Do not use other character encodings.


### Indenting

Use an indent of 4 spaces. Do not use tabs.

> N.b.: Using only spaces, and not mixing spaces with tabs, helps to avoid
> problems with diffs, patches, history, and annotations. The use of spaces
> also makes it easy to insert fine-grained sub-indentation for inter-line 
> alignment.


### Lines

There is no hard limit on line length. The soft limit on line length is 120
characters; automated style checkers must warn but not error at the soft
limit. This guide encourages lines no longer than 80 characters, and
encourages splitting longer lines into multiple subsequent lines of no more
than 80 characters each.

Use the Unix LF (linefeed) line ending on all PHP files.

Do not add trailing spaces at the end of lines.

Blank lines may be added to improve readability and to indicate related blocks
of code.

Use at most one statement per line.


`namespace`, `use`, and `class`
-------------------------------

> N.b.: Formal namespaces were introduced in PHP 5.3. Code written for 5.2.x
> and before must use the pseudo-namespacing convention of `Vendor_`
> prefixes on class names. Code written for PHP 5.3 and after must use
> formal namespaces.

All namespaces and classes are to be named with [PSR-0][] in mind. This means each
class is in a file by itself, and is in a namespace of at least one level: a
top-level vendor name.

[PSR-0]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-0.md
  
The `namespace` line has one blank line after it.

All `use` declarations go after the `namespace` declaration. There is one
`use` keyword per declaration.

The `use` block has one blank line after it.
    
Class names are in `StudlyCaps`. The opening and closing braces for the
class go on their own line.

The `extends` and `implements` keywords are on the same line as the class
name.

```php
<?php
namespace Vendor\Package;

use FooClass;
use BarClass as Bar;
use OtherVendor\OtherPackage\BazClass;

class ClassName extends ParentClass implements InterfaceName
{
    // constants, properties, methods
}
```

Lists of `implements` may be split across multiple lines, where each
subsequent line is indented once. List only one interface per line.

```php
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
```

Class Constants, Properties, and Methods
----------------------------------------

> N.b.: The term "class" refers to all classes, interfaces, and traits.


### Constants

Class constants are all upper case with underscore separators.

```php
<?php
namespace Vendor\Package;

class ClassName
{
    const CONSTANT_NAME = 'constant value';
}
```

### Properties

This guide expressly avoids any recommendation regarding the use of
`$StudlyCaps`, `$camelCase`, or `$under_score` property names. It is often the
case that property names map directly to field names in external data sources.
Changing between naming conventions when changing contexts merely to suit a
style guide would be counterproductive in such cases.

Whatever naming convention is used must be applied consistently within a
reasonable scope. That scope may be vendor-level, package-level, class-level,
or method-level.

Declare visibility on all properties; do not use `var` to declare a property,
and declare only one property per statement. Some projects prefix property
names with a single underscore to indicate protected or private visibility;
this guide discourages but does not disallow that practice.

A property declaration looks like the following.

```php
<?php
namespace Vendor\Package;

class ClassName
{
    public $foo = null;
}
```

### Methods

Declare method names in `camelCase()` with no space after the method name. The
opening and closing braces go on their own line. There is no space after the
opening parenthesis, and there is no space before the closing parenthesis.

Declare visibility on all methods. Some projects prefix method names with a
single underscore to indicate protected or private visibility; this guide
discourages but does not disallow that practice.

A method declaration looks like the following. Note the placement of
parentheses, commas, spaces, and braces:

```php
<?php
namespace Vendor\Package;

class ClassName
{
    public function fooBarBaz($arg1, &$arg2, $arg3 = [])
    {
        // method body
    }
}
```    

### Method Arguments

Method arguments with default values always go at the end of the argument
list.

```php
<?php
namespace Vendor\Package;

class ClassName
{
    public function foo($arg1, &$arg2, $arg3 = [])
    {
        // method body
    }
}
```
    
Argument lists may be split across subsequent indented lines; list only one
argument per line. When the argument list is split across multiple lines, the
closing parenthesis and opening brace are placed together on their own line.

```php
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
```

### `abstract`, `final`, and `static`

When present, the `abstract` and `final` declarations precede the
visibility declaration.

When present, the `static` declaration comes after the visibility declaration.

```php
<?php
namespace Vendor\Package;

class ClassName
{
    protected static $foo;

    abstract protected zim();

    final public static bar()
    {
        // method body
    }
}
```

Control Structures
------------------

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


### `if`, `elseif`, `else`

An `if` structure looks like the following. Note the placement of parentheses,
spaces, and braces; and that `else` and `elseif` are on the same line as the
closing brace from the earlier body.

```php
<?php
if ($expr1) {
    // if body
} elseif ($expr2) {
    // elseif body
} else {
    // else body;
}
```

> N.b.: There appears to be no consistency between projects, and often not
> even within the same project, on the use of `else if` vs `elseif`. This
> guide encourages the use of `elseif` so that all control structures look
> like single words.


### `switch`, `case`

A `switch` structure looks like the following. Note the placement of
parentheses, spaces, and braces; the indent levels for `case` and `break`; and
the presence of a `// no break` comment when a break is intentionally omitted.

```php
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
```

### `while`, `do while`

A `while` statement looks like the following. Note the placement of
parentheses, spaces, and braces.

```php
<?php
while ($expr) {
    // structure body
}
```

Similarly, a `do while` statement looks like the following. Note the placement
of parentheses, spaces, and braces.

```php
<?php
do {
    // structure body;
} while ($expr);
```

### `for`

A `for` statement looks like the following. Note the placement of parentheses,
spaces, and braces.

```php
<?php
for ($i = 0; $i < 10; $i++) {
    // for body
}
```

### `foreach`
    
A `foreach` statement looks like the following. Note the placement of
parentheses, spaces, and braces.

```php
<?php
foreach ($iterable as $key => $value) {
    // foreach body
}
```

### `try`, `catch`

A `try catch` block looks like the following. Note the placement of
parentheses, spaces, and braces.

```php
<?php
try {
    // try body
} catch (FirstExceptionType $e) {
    // catch body
} catch (OtherExceptionType $e) {
    // catch body
}
```

Conclusion
----------

There are many elements of style and practice intentionally omitted by this
guide; these include but are not limited to:

- Declaration of global variables and global constants

- Declaration of functions outside classes, including anonymous functions
  and closures

- Operators and assignment

- Inter-line alignment

- Comments and documentation blocks

- Class name prefixes and suffixes

- Best practices

Future PSRs may revise and extend this guide to address those or other
elements of style and practice.


Appendices
----------

### Survey Data

    url,http://www.horde.org/apps/horde/docs/CODING_STANDARDS,http://pear.php.net/manual/en/standards.php,http://solarphp.com/manual/appendix-standards.style,http://framework.zend.com/manual/en/coding-standard.html,http://symfony.com/doc/2.0/contributing/code/standards.html,http://www.ppi.io/docs/coding-standards.html,https://github.com/ezsystems/ezp-next/wiki/codingstandards,http://book.cakephp.org/2.0/en/contributing/cakephp-coding-conventions.html,https://github.com/UnionOfRAD/lithium/wiki/Spec%3A-Coding,http://drupal.org/coding-standards,http://code.google.com/p/sabredav/,http://area51.phpbb.com/docs/31x/coding-guidelines.html,https://docs.google.com/a/zikula.org/document/edit?authkey=CPCU0Us&hgd=1&id=1fcqb93Sn-hR9c0mkN6m_tyWnmEvoswKBtSc0tKkZmJA,http://www.chisimba.com,n/a,https://github.com/Respect/project-info/blob/master/coding-standards-sample.php,n/a,Object Calisthenics for PHP,http://doc.nette.org/en/coding-standard,http://flow3.typo3.org,https://github.com/propelorm/Propel2/wiki/Coding-Standards,http://developer.joomla.org/coding-standards.html
    voting,yes,yes,yes,yes,yes,yes,yes,yes,yes,yes,yes,yes,yes,yes,yes,no,no,no,?,yes,no,yes
    indent_type,4,4,4,4,4,tab,4,tab,tab,2,4,tab,4,4,4,4,4,4,tab,tab,4,tab
    line_length_limit_soft,75,75,75,75,no,85,120,120,80,80,80,no,100,80,80,?,?,120,80,120,no,150
    line_length_limit_hard,85,85,85,85,no,no,no,no,100,?,no,no,no,100,100,?,120,120,no,no,no,no
    class_names,studly,studly,studly,studly,studly,studly,studly,studly,studly,studly,studly,lower_under,studly,lower,studly,studly,studly,studly,?,studly,studly,studly
    class_brace_line,next,next,next,next,next,same,next,same,same,same,same,next,next,next,next,next,next,next,next,same,next,next
    constant_names,upper,upper,upper,upper,upper,upper,upper,upper,upper,upper,upper,upper,upper,upper,upper,upper,upper,upper,upper,upper,upper,upper
    true_false_null,lower,lower,lower,lower,lower,lower,lower,lower,lower,upper,lower,lower,lower,upper,lower,lower,lower,lower,lower,upper,lower,lower
    method_names,camel,camel,camel,camel,camel,camel,camel,camel,camel,camel,camel,lower_under,camel,camel,camel,camel,camel,camel,camel,camel,camel,camel
    method_brace_line,next,next,next,next,next,same,next,same,same,same,same,next,next,same,next,next,next,next,next,same,next,next
    control_brace_line,same,same,same,same,same,same,next,same,same,same,same,next,same,same,next,same,same,same,same,same,same,next
    control_space_after,yes,yes,yes,yes,yes,no,yes,yes,yes,yes,no,yes,yes,yes,yes,yes,yes,yes,yes,yes,yes,yes
    always_use_control_braces,yes,yes,yes,yes,yes,yes,no,yes,yes,yes,no,yes,yes,yes,yes,no,yes,yes,yes,yes,yes,yes
    else_elseif_line,same,same,same,same,same,same,next,same,same,next,same,next,same,next,next,same,same,same,same,same,same,next
    case_break_indent_from_switch,0/1,0/1,0/1,1/2,1/2,1/2,1/2,1/1,1/1,1/2,1/2,1/1,1/2,1/2,1/2,1/2,1/2,1/2,0/1,1/1,1/2,1/2
    function_space_after,no,no,no,no,no,no,no,no,no,no,no,no,no,no,no,no,no,no,no,no,no,no
    closing_php_tag_required,no,no,no,no,no,no,no,no,yes,no,no,no,no,yes,no,no,no,no,no,yes,no,no
    line_endings,LF,LF,LF,LF,LF,LF,LF,LF,?,LF,?,LF,LF,LF,LF,?,,LF,?,LF,LF,LF
    static_or_visibility_first,static,?,static,either,either,either,visibility,visibility,visibility,either,static,either,?,visibility,?,?,either,either,visibility,visibility,static,?
    control_space_parens,no,no,no,no,no,no,yes,no,no,no,no,no,no,yes,?,no,no,no,no,no,no,no
    blank_line_after_php,no,no,no,no,yes,no,no,no,no,yes,yes,no,no,yes,?,yes,yes,no,yes,no,yes,no
    class_method_control_brace,next/next/same,next/next/same,next/next/same,next/next/same,next/next/same,same/same/same,next/next/next,same/same/same,same/same/same,same/same/same,same/same/same,next/next/next,next/next/same,next/same/same,next/next/next,next/next/same,next/next/same,next/next/same,next/next/same,same/same/same,next/next/same,next/next/next


### Survey Results

    indent_type:
        tab: 7
        2: 1
        4: 14
    line_length_limit_soft:
        ?: 2
        no: 3
        75: 4
        80: 6
        85: 1
        100: 1
        120: 4
        150: 1
    line_length_limit_hard:
        ?: 2
        no: 11
        85: 4
        100: 3
        120: 2
    class_names:
        ?: 1
        lower: 1
        lower_under: 1
        studly: 19
    class_brace_line:
        next: 16
        same: 6
    constant_names:
        upper: 22
    true_false_null:
        lower: 19
        upper: 3
    method_names:
        camel: 21
        lower_under: 1
    method_brace_line:
        next: 15
        same: 7
    control_brace_line:
        next: 4
        same: 18
    control_space_after:
        no: 2
        yes: 20
    always_use_control_braces:
        no: 3
        yes: 19
    else_elseif_line:
        next: 6
        same: 16
    case_break_indent_from_switch:
        0/1: 4
        1/1: 4
        1/2: 14
    function_space_after:
        no: 22
    closing_php_tag_required:
        no: 19
        yes: 3
    line_endings:
        ?: 5
        LF: 17
    static_or_visibility_first:
        ?: 5
        either: 7
        static: 4
        visibility: 6
    control_space_parens:
        ?: 1
        no: 19
        yes: 2
    blank_line_after_php:
        ?: 1
        no: 13
        yes: 8
    class_method_control_brace:
        next/next/next: 4
        next/next/same: 11
        next/same/same: 1
        same/same/same: 6

