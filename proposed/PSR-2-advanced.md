Advanced Coding Style
=====================

This guide extends and expands on [PSR-1][], the basic coding standard.

The intent of this guide is to reduce cognitive friction when scanning code
from different authors. It does so by enumerating a shared set of rules and
expectations about how to format PHP code.

The style rules herein are derived from commonalities among the various member
projects. If one project has its own style guide, that's fine for that
project. But when various authors collaborate across multiple projects, it
helps to have one set of guidelines to be used among all those projects. Thus,
the benefit of this guide is not in the rules themselves, but in the sharing
of those rules.


1. Overview
-----------

- Follow [PSR-1][].

- Use 4 spaces for indenting, not tabs.

- There is no hard limit on line length; the soft limit is 120 characters;
  lines of 80 characters or less are encouraged.

- Place one blank line after the `namespace` declaration, and one blank line
  after the block of `use` declarations.

- Opening braces for classes go on the next line, and closing braces go on
  their own line.

- Opening braces for methods go on the next line, and closing braces go on
  their own line.

- Declare visibility on all properties and methods; declare `abstract` and
  `final` before the visibility, and declare `static` after the visibility.
  
- Control structure keywords have one space after them; function and method
  calls do not.

- Opening braces for control structures go on the same line, and closing
  braces go on their own line.

- Opening parentheses for control structures have no space after them, and
  closing parentheses for control structures have no space before.


2. General
----------

### 2.1 Basic Coding Standard

Follow all rules outlined in PSR-1 "Basic Coding Standard".


### 2.2 Files

Use the Unix LF (linefeed) line ending on all PHP files.

End each file with a single blank line.

In files that contain only PHP, omit the closing `?>` tag.

### 2.3 Lines

There is no hard limit on line length.

The soft limit on line length is 120 characters; automated style checkers must
warn but not error at the soft limit.

This guide encourages lines no longer than 80 characters, and encourages
splitting longer lines into multiple subsequent lines of no more than 80
characters each.

Do not add trailing spaces at the end of lines.

Blank lines may be added to improve readability and to indicate related
blocks of code.

Use at most one statement per line.

### 2.4 Indenting

Use an indent of 4 spaces. Do not use tabs.

> N.b.: Using only spaces, and not mixing spaces with tabs, helps to avoid
> problems with diffs, patches, history, and annotations. The use of spaces
> also makes it easy to insert fine-grained sub-indentation for inter-line 
> alignment.


3. Namespace and Use Declarations
---------------------------------

Place one blank line after the `namespace` declaration.

All `use` declarations go after the `namespace` declaration. There is one
`use` keyword per declaration.

Place one blank line after the `use` block.

For example:

```php
<?php
namespace Vendor\Package;

use FooClass;
use BarClass as Bar;
use OtherVendor\OtherPackage\BazClass;

// ... additional PHP code ...

```


4. Classes, Properties, and Methods
-----------------------------------

The term "class" refers to all classes, interfaces, and traits.

### 4.1. Extends and Implements

Declare `extends` and `implements` keywords on the same line as the class
name.

The opening and closing braces for the class go on their own line.

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

### 4.2. Properties

Declare visibility on all properties. Do not use `var` to declare a property.
Declare only one property per statement.

> N.b. Some projects prefix property names with a single underscore to indicate
> protected or private visibility. This guide discourages but does not
> disallow that practice.

A property declaration looks like the following.

```php
<?php
namespace Vendor\Package;

class ClassName
{
    public $foo = null;
}
```

### 4.3 Methods

Declare visibility on all methods.

> N.b. Some projects prefix method names with a single underscore to indicate
> protected or private visibility. This guide discourages but does not
> disallow that practice.

Declare methods with no space after the method name. The opening and closing
braces go on their own line. There is no space after the opening parenthesis,
and there is no space before the closing parenthesis.

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

### 4.3 Method Arguments

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

### 4.4. `abstract`, `final`, and `static`

When present, the `abstract` and `final` declarations precede the visibility
declaration.

When present, the `static` declaration comes after the visibility declaration.

```php
<?php
namespace Vendor\Package;

class ClassName
{
    protected static $foo;

    abstract protected function zim();

    final public static function bar()
    {
        // method body
    }
}
```


5. Control Structures
---------------------

The general style rules for control structures are as follows:

- one space after the control structure keyword
- no space after the opening parenthesis
- no space before the closing parenthesis
- one space between the closing parenthesis and the opening brace
- structure body indented once
- closing brace on the line after the body, outdented once from the body

**Always use braces to enclose the body of each structure.** This standardizes how
the structures look, and reduces the likelihood of introducing errors as new
lines get added to the body.


### 5.1. `if`, `elseif`, `else`

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


### 5.2. `switch`, `case`

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

### 5.3 `while`, `do while`

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

### 5.4. `for`

A `for` statement looks like the following. Note the placement of parentheses,
spaces, and braces.

```php
<?php
for ($i = 0; $i < 10; $i++) {
    // for body
}
```

### 5.5. `foreach`
    
A `foreach` statement looks like the following. Note the placement of
parentheses, spaces, and braces.

```php
<?php
foreach ($iterable as $key => $value) {
    // foreach body
}
```

### 5.6. `try`, `catch`

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

6. Conclusion
--------------

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


7. Appendices
-------------

### 7.1 Survey Data

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

### 7.2 Survey Legend

**`indent_type`**: The type of indenting. `tab` = "Use a tab", `2` or `4` =
"number of spaces"

**`line_length_limit_soft`**: The "soft" line length limit, in characters. `?`
= not discernible or no response, `no` means no limit.

**`line_length_limit_hard`**: The "hard" line length limit, in characters. `?`
= not discernible or no response, `no` means no limit.

**`class_names`**: How classes are named. `lower` = lowercase only,
`lower_under` = lowercase with underscore separators, `studly` = StudlyCase.

**`class_brace_line`**: Does the opening brace for a class go on the `same`
line as the class keyword, or on the `next` line after it?

**`constant_names`**: How are class constants named? `upper` = Uppercase with
underscore separators.

**`true_false_null`**: Are the `true`, `false`, and `null` keywords spelled as
all `lower` case, or all `upper` case?

**`method_names`**: How are methods named? `camel` = `camelCase`,
`lower_under` = lowercase with underscore separators.

**`method_brace_line`**: Does the opening brace for a method go on the `same`
line as the method name, or on the `next` line?

**`control_brace_line`**: Does the opening brace for a control structure go on
the `same` line, or on the `next` line?

**`control_space_after`**: Is there a space after the control structure
keyword?

**`always_use_control_braces`**: Do control structures always use braces?

**`else_elseif_line`**: When using `else` or `elseif`, does it go on the
`same` line as the previous closing brace, or does it go on the `next` line?

**`case_break_indent_from_switch`**: How many times are `case` and `break`
indented from an opening `switch` statement?

**`function_space_after`**: Do function calls have a space after the function
name and before the opening parenthesis?

**`closing_php_tag_required`**: In files containing only PHP, is the closing
`?>` tag required?

**`line_endings`**: What type of line ending is used?

**`static_or_visibility_first`**: When declaring a method, does `static` come
first, or does the visibility come first?

**`control_space_parens`**: In a control structure expression, is there a
space after the opening parenthesis and a space before the closing
parenthesis? `yes` = `if ( $expr )`, `no` = `if ($expr)`.

**`blank_line_after_php`**: Is there a blank line after the opening PHP tag?

**`class_method_control_brace`**: A summary of what line the opening braces go
on for classes, methods, and control structures.

### 7.3 Survey Results

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
