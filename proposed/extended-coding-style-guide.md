Extended Coding Style Guide
===========================

The key words "MUST", "MUST NOT", "REQUIRED", "SHALL", "SHALL NOT", "SHOULD",
"SHOULD NOT", "RECOMMENDED", "MAY", and "OPTIONAL" in this document are to be
interpreted as described in [RFC 2119][].

[RFC 2119]: http://tools.ietf.org/html/rfc2119

1. Overview
-----------

This guide extends and expands on [PSR-2][], the coding style guide and
[PSR-1][], the basic coding standard.

Like [PSR-2][], the intent of this guide is to reduce cognitive friction when scanning
code from different authors. It does so by enumerating a shared set of rules and
expectations about how to format PHP code. This PSR seeks to provide a set way that
coding style tools can implement, projects can declare adherence to and developers
can easily relate to between different projects. When various authors collaborate
across multiple projects, it helps to have one set of guidelines to be used among
all those projects. Thus, the benefit of this guide is not in the rules themselves
but the sharing of those rules.

[PSR-2][] was accepted in 2012 and since then a number of changes have been made to PHP
which have implications for coding style guidelines. Whilst [PSR-2] is very comprehensive
of PHP functionality that existed at the time of writing, new functionality is very
open to interpretation. This PSR therefore seeks to clarify the content of PSR-2 in
a more modern context with new functionality available, and make the errata to PSR-2
binding.

### Overview

Throughout this document, any instructions MAY be ignored if they do not exist in versions
of PHP supported by your project.

### Example

This example encompasses some of the rules below as a quick overview:

```php
<?php
declare(strict_types=1);

namespace Vendor\Package;

use Vendor\Package\{ClassA as A, ClassB, ClassC as C};
use Vendor\Package\Namespace\ClassD as D;

use function Vendor\Package\{func_a, func_b, func_c};
use const Vendor\Package\{ConstantA, ConstantB, ConstantC};

class Foo extends Bar implements FooInterface
{
    public function sampleFunction(int $a, int $b = null): array
    {
        if ($a === $b) {
            bar();
        } elseif ($a > $b) {
            $foo->bar($arg1);
        } else {
            BazClass::bar($arg2, $arg3);
        }
    }

    final public static function bar()
    {
        // method body
    }
}
```

General
----------

### Basic Coding Standard

Code MUST follow all rules outlined in [PSR-1].

### Files

All PHP files MUST use the Unix LF (linefeed) line ending.

All PHP files MUST end with a single containing only a single newline (LF) character.

The closing `?>` tag MUST be omitted from files containing only PHP.

### Lines

There MUST NOT be a hard limit on line length.

The soft limit on line length MUST be 120 characters; automated style checkers
MUST warn but MUST NOT error at the soft limit.

Lines SHOULD NOT be longer than 80 characters; lines longer than that SHOULD
be split into multiple subsequent lines of no more than 80 characters each.

There MUST NOT be trailing whitespace at the end of non-blank lines.

Blank lines MAY be added to improve readability and to indicate related
blocks of code except where explictly forbidden.

There MUST NOT be more than one statement per line.

### Indenting

Code MUST use an indent of 4 spaces, and MUST NOT use tabs for indenting.

### Keywords and True/False/Null

PHP [keywords] MUST be in lower case.

The PHP reserved words `int`, `true`, `object`, `float`, `false`, `mixed`,
`bool`, `null`, `numeric`, `string` and `resource MUST be in lower case

Namespace, Strict Types and Use Declarations
--------------------------------------------

When present, there MUST be one blank line before the `namespace` declaration.

When present, there MUST be one blank line after the `namespace` declaration.

When present, all `use` declarations MUST go after the `namespace`
declaration.

There MUST be one `use` keyword per declaration.

When using multiple classes, functions or constants within one namespace, you
MUST group use statements within one namespace.

Use statements MUST be in a block according to the entity (class, function or group)
which is being grouped. Within each block there MUST be no blank lines. If a block
has multiple lines there MUST be a blank line before the first line and a blank line
after the last line.

Classes, functions or constants grouped together into a single line must be listed
alphabetically.

The groups MUST be ordered such that classes are first, followed by functions and
then constants.

For example:

```php
<?php
declare(strict_types=1);

namespace Vendor\Package;

use Vendor\Package\{ClassA as A, ClassB, ClassC as C};
use Vendor\Package\Namespace\ClassD as D;
use Vendor\Package\AnotherNamespace\ClassE as E;

use function Vendor\Package\{func_a, func_b, func_c};
use const Vendor\Package\{ConstantA, ConstantB, ConstantC};

class FooBar
{
    // ... additional PHP code ...
}

```

All files MUST declare strict types.

Files containing only PHP MUST contain the strict type declarations on the
first line preceeding the opening PHP tag.

There MUST not be a blank line before the strict types declaration.

For example:

```php
<?php
declare(strict_types=1);

namespace Vendor\Package;

// ... additional PHP code ...

```

Files containing HTML outside PHP opening and closing tags MUST on the first
line include an opening php tag, the strict types declaration and closing
tag.

For example:
```php
<?php declare(strict_types=1); ?>
<html>
<body>
    <?php
        // ... additional PHP code ...
    ?>
</body>
</html>
```


4. Classes, Properties, and Methods
-----------------------------------

The term "class" refers to all classes, interfaces, and traits.

### Extends and Implements

The `extends` and `implements` keywords MUST be declared on the same line as
the class name.

The opening brace for the class MUST go on its own line; the closing brace
for the class MUST go on the next line after the body.

```php
<?php
namespace Vendor\Package;

use FooClass;
use BarClass as Bar;
use OtherVendor\OtherPackage\BazClass;

class ClassName extends ParentClass implements \ArrayAccess, \Countable
{
    // constants, properties, methods
}
```

Lists of `implements` MAY be split across multiple lines, where each
subsequent line is indented once. When doing so, the first item in the list
MUST be on the next line, and there MUST be only one interface per line.

```php
<?php
namespace Vendor\Package;

use FooClass;
use BarClass as Bar;
use OtherVendor\OtherPackage\BazClass;

class ClassName extends ParentClass implements
    \ArrayAccess,
    \Countable,
    \Serializable
{
    // constants, properties, methods
}
```

### Properties

Visibility MUST be declared on all properties.

The `var` keyword MUST NOT be used to declare a property.

There MUST NOT be more than one property declared per statement.

Property names SHOULD NOT be prefixed with a single underscore to indicate
protected or private visibility.

A property declaration looks like the following.

```php
<?php
namespace Vendor\Package;

class ClassName
{
    public $foo = null;
}
```

### Methods and Functions

Visibility MUST be declared on all methods.

Method names SHOULD NOT be prefixed with a single underscore to indicate
protected or private visibility.

Method and Function names MUST NOT be declared with a space after the method name. The
opening brace MUST go on its own line, and the closing brace MUST go on the
next line following the body. There MUST NOT be a space after the opening
parenthesis, and there MUST NOT be a space before the closing parenthesis.

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

A function declaration looks like the following. Note the placement of
parentheses, commas, spaces, and braces:

```php
<?php

function fooBarBaz($arg1, &$arg2, $arg3 = [])
{
    // function body
}
```

### Method and function Arguments

In the argument list, there MUST NOT be a space before each comma, and there
MUST be one space after each comma.

Method and function arguments with default values MUST go at the end of the argument
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

Argument lists MAY be split across multiple lines, where each subsequent line
is indented once. When doing so, the first item in the list MUST be on the
next line, and there MUST be only one argument per line.

When the argument list is split across multiple lines, the closing parenthesis
and opening brace MUST be placed together on their own line with one space
between them.

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

When present, the `abstract` and `final` declarations MUST precede the
visibility declaration.

When present, the `static` declaration MUST come after the visibility
declaration.

```php
<?php
namespace Vendor\Package;

abstract class ClassName
{
    protected static $foo;

    abstract protected function zim();

    final public static function bar()
    {
        // method body
    }
}
```

### Method and Function Calls

When making a method or function call, there MUST NOT be a space between the
method or function name and the opening parenthesis, there MUST NOT be a space
after the opening parenthesis, and there MUST NOT be a space before the
closing parenthesis. In the argument list, there MUST NOT be a space before
each comma, and there MUST be one space after each comma.

```php
<?php
bar();
$foo->bar($arg1);
Foo::bar($arg2, $arg3);
```

Argument lists MAY be split across multiple lines, where each subsequent line
is indented once. When doing so, the first item in the list MUST be on the
next line, and there MUST be only one argument per line.

```php
<?php
$foo->bar(
    $longArgument,
    $longerArgument,
    $muchLongerArgument
);
```

Control Structures
---------------------

The general style rules for control structures are as follows:

- There MUST be one space after the control structure keyword
- There MUST NOT be a space after the opening parenthesis
- There MUST NOT be a space before the closing parenthesis
- There MUST be one space between the closing parenthesis and the opening
  brace
- The structure body MUST be indented once
- The closing brace MUST be on the next line after the body

The body of each structure MUST be enclosed by braces. This standardizes how
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

The keyword `elseif` SHOULD be used instead of `else if` so that all control
keywords look like single words.


### `switch`, `case`

A `switch` structure looks like the following. Note the placement of
parentheses, spaces, and braces. The `case` statement MUST be indented once
from `switch`, and the `break` keyword (or other terminating keyword) MUST be
indented at the same level as the `case` body. There MUST be a comment such as
`// no break` when fall-through is intentional in a non-empty `case` body.

```php
<?php
switch ($expr) {
    case 0:
        echo 'First case, with a break';
        break;
    case 1:
        echo 'Second case, which falls through';
        // no break
    case 2:
    case 3:
    case 4:
        echo 'Third case, return instead of break';
        return;
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

Closures
-----------

Closures MUST be declared with a space after the `function` keyword, and a
space before and after the `use` keyword.

The opening brace MUST go on the same line, and the closing brace MUST go on
the next line following the body.

There MUST NOT be a space after the opening parenthesis of the argument list
or variable list, and there MUST NOT be a space before the closing parenthesis
of the argument list or variable list.

In the argument list and variable list, there MUST NOT be a space before each
comma, and there MUST be one space after each comma.

Closure arguments with default values MUST go at the end of the argument
list.

A closure declaration looks like the following. Note the placement of
parentheses, commas, spaces, and braces:

```php
<?php
$closureWithArgs = function ($arg1, $arg2) {
    // body
};

$closureWithArgsAndVars = function ($arg1, $arg2) use ($var1, $var2) {
    // body
};
```

Argument lists and variable lists MAY be split across multiple lines, where
each subsequent line is indented once. When doing so, the first item in the
list MUST be on the next line, and there MUST be only one argument or variable
per line.

When the ending list (whether or arguments or variables) is split across
multiple lines, the closing parenthesis and opening brace MUST be placed
together on their own line with one space between them.

The following are examples of closures with and without argument lists and
variable lists split across multiple lines.

```php
<?php
$longArgs_noVars = function (
    $longArgument,
    $longerArgument,
    $muchLongerArgument
) {
   // body
};

$noArgs_longVars = function () use (
    $longVar1,
    $longerVar2,
    $muchLongerVar3
) {
   // body
};

$longArgs_longVars = function (
    $longArgument,
    $longerArgument,
    $muchLongerArgument
) use (
    $longVar1,
    $longerVar2,
    $muchLongerVar3
) {
   // body
};

$longArgs_shortVars = function (
    $longArgument,
    $longerArgument,
    $muchLongerArgument
) use ($var1) {
   // body
};

$shortArgs_longVars = function ($arg) use (
    $longVar1,
    $longerVar2,
    $muchLongerVar3
) {
   // body
};
```

Note that the formatting rules also apply when the closure is used directly
in a function or method call as an argument.

```php
<?php
$foo->bar(
    $arg1,
    function ($arg2) use ($var1) {
        // body
    },
    $arg3
);
```

Anonymous Classes
--------------------

Anonymous Classes MUST be [PSR-2][] section 4 compatible barring the following
exceptions.


```
<?php

$instance = new class {};
```

The opening bracket MAY be on the same line as the `class` keyword so long as
the list of `implements` interfaces does not wrap. If the list of interfaces
wraps, the bracket MUST be placed on the line immediately following the last
interface.

```
<?php

// Bracket on the same line
$instance = new class extends \Foo implements \HandleableInterface {

};

// Bracket on the next line
$instance = new class extends \Foo implements
    \ArrayAccess,
    \Countable,
    \Serializable
{

};


```

[PSR-1]: http://www.php-fig.org/psr/psr-1/
[PSR-2]: http://www.php-fig.org/psr/psr-2/
[keywords]: http://php.net/manual/en/reserved.keywords.php