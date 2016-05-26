Extended Coding Style Guide
===========================

The key words "MUST", "MUST NOT", "REQUIRED", "SHALL", "SHALL NOT", "SHOULD",
"SHOULD NOT", "RECOMMENDED", "MAY", and "OPTIONAL" in this document are to be
interpreted as described in [RFC 2119][].

[RFC 2119]: http://tools.ietf.org/html/rfc2119

1. Overview
-----------

This specification extends, expands and replaces [PSR-2][], the coding style guide and
requires adherance to [PSR-1][], the basic coding standard.

Like [PSR-2][], the intent of this specification is to reduce cognitive friction when
scanning code from different authors. It does so by enumerating a shared set of rules
and expectations about how to format PHP code. This PSR seeks to provide a set way that
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

~~~php
<?php
declare(strict_types=1);

namespace Vendor\Package;

use Vendor\Package\{ClassA as A, ClassB, ClassC as C};
use Vendor\Package\Namespace\ClassD as D;

use function Vendor\Package\{functionA, functionB, functionC};
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
~~~

2. General
----------

### 2.1 Basic Coding Standard

Code MUST follow all rules outlined in [PSR-1].

The term 'StudlyCaps' in PSR-1 MUST be interpreted as PascalCase where the first letter of
each word is capitalised including the very first letter.

### 2.2 Files

All PHP files MUST use the Unix LF (linefeed) line ending.

All PHP files MUST end with a single line, containing only a single newline (LF) character.

The closing `?>` tag MUST be omitted from files containing only PHP.

### 2.3 Lines

There MUST NOT be a hard limit on line length.

The soft limit on line length MUST be 120 characters; automated style checkers
MUST warn but MUST NOT error at the soft limit.

Lines SHOULD NOT be longer than 80 characters; lines longer than that SHOULD
be split into multiple subsequent lines of no more than 80 characters each.

There MUST NOT be trailing whitespace at the end of lines.

Blank lines MAY be added to improve readability and to indicate related
blocks of code except where explictly forbidden.

There MUST NOT be more than one statement per line.

### 2.4 Indenting

Code MUST use an indent of 4 spaces for each indent level, and MUST NOT use
tabs for indenting.

### 2.5 Keywords and Types

PHP [keywords][] MUST be in lower case.

The PHP types and keywords `array`, `int`, `true`, `object`, `float`, `false`, `mixed`,
`bool`, `null`, `numeric`, `string`, `void` and `resource` MUST be in lower case.

3. Declare Statements, Namespace, and Use Declarations
--------------------------------------------

When present, there MUST be one blank line after the `declare` statement(s)
e.g. `declare(ticks=);`

There MUST NOT be a blank line before declare statements such as those for strict
types or ticks. They MUST be contained on the lines immediately following the
opening tag (which must be on the first line when declare statement(s) are present).

Each declare statement (e.g. `declare(ticks=);`) MUST be on its own line.

When the opening `<?php` tag is on the first line of the file, it MUST be on its
own line with no other statements unless it is a file containing markup outside of PHP
opening and closing tags.

When present, there MUST be one blank line after the `namespace` declaration.

When present, the `namespace` declaration MUST be on its own line.

When present, all `use` declarations MUST go after the `namespace`
declaration.

There MUST be one `use` keyword per declaration.

Use statements MUST be in blocks, grouped by varying entity (classes [inc. interfaces and traits],
functions or constants). To elaborate, this means that any and all classes are in a block
together; any and all functions are in a block together; and any and all constants must
be grouped together. Within each block there MUST be no blank lines. If a block has
multiple lines there MUST be a blank line before the first line and a blank line after
the last line.

The groups MUST be ordered such that classes (together with interfaces and traits) are first,
followed by functions and then constants.

Example of the above notices about namespace, strict types and use declarations:

~~~php
<?php
declare(strict_types=1);

namespace Vendor\Package;

use Vendor\Package\{ClassA as A, ClassB, ClassC as C};
use Vendor\Package\Namespace\ClassD as D;
use Vendor\Package\AnotherNamespace\ClassE as E;

use function Vendor\Package\{functionA, functionB, functionC};
use const Vendor\Package\{ConstantA, ConstantB, ConstantC};

class FooBar
{
    // ... additional PHP code ...
}

~~~

Compound namespaces with a depth of two or more MUST not be used. Therefore the
following is the maximum compounding depth allowed:
~~~php
<?php

use Vendor\Package\Namespace\{
    SubnamespaceOne\ClassA,
    SubnamespaceOne\ClassB,
    SubnamespaceTwo\ClassY,
    ClassZ,
};
~~~

And the following would not be allowed:
~~~php
<?php

use Vendor\Package\Namespace\{
    SubnamespaceOne\AnotherNamespace\ClassA,
    SubnamespaceOne\ClassB,
    ClassZ,
};
~~~

When wishing to declare strict types in files containing markup outside PHP
opening and closing tags MUST, on the first line, include an opening php tag,
the strict types declaration and closing tag.

For example:
~~~php
<?php declare(strict_types=1); ?>
<html>
<body>
    <?php
        // ... additional PHP code ...
    ?>
</body>
</html>
~~~

Declare statements MUST contain no spaces and MUST look like `declare(strict_types=1);`.

Block declare statements are allowed and MUST be formatted as below. Note position of
braces and spacing:
~~~php
declare(ticks=1) {
    //some code
}
~~~

4. Classes, Properties, and Methods
-----------------------------------

The term "class" refers to all classes, interfaces, and traits.

Any closing brace must not be followed by any comment or statement on the
same line.

When instantiating a new class, parenthesis MUST always be present even when
there are no arguments passed to the constructor.

~~~php
new Foo();
~~~

### 4.1 Extends and Implements

The `extends` and `implements` keywords MUST be declared on the same line as
the class name.

The opening brace for the class MUST go on its own line; the closing brace
for the class MUST go on the next line after the body.

Opening braces MUST be on their own line and MUST NOT be preceded or followed
by a blank line.

Closing braces MUST be on their own line and MUST NOT be preceded by a blank
line.

~~~php
<?php
namespace Vendor\Package;

use FooClass;
use BarClass as Bar;
use OtherVendor\OtherPackage\BazClass;

class ClassName extends ParentClass implements \ArrayAccess, \Countable
{
    // constants, properties, methods
}
~~~

Lists of `implements` and `extends` MAY be split across multiple lines, where
each subsequent line is indented once. When doing so, the first item in the
list MUST be on the next line, and there MUST be only one interface per line.

~~~php
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
~~~

### 4.2 Using traits

The `use` keyword used inside the classes to implement traits MUST be
declared on the next line after the opening brace.

~~~php
<?php
namespace Vendor\Package;

use Vendor\Package\FirstTrait;

class ClassName
{
    use FirstTrait;
}
~~~

Each individual Trait that is imported into a class MUST be included
one-per-line, and each inclusion MUST have its own `use` statement.

~~~php
<?php
namespace Vendor\Package;

use Vendor\Package\FirstTrait;
use Vendor\Package\SecondTrait;
use Vendor\Package\ThirdTrait;

class ClassName
{
    use FirstTrait;
    use SecondTrait;
    use ThirdTrait;
}
~~~

When the class has nothing after the `use` declaration, the class
closing brace MUST be on the next line after the `use` declaration.

~~~php
<?php
namespace Vendor\Package;

use Vendor\Package\FirstTrait;

class ClassName
{
    use FirstTrait;
}
~~~

Otherwise it MUST have a blank line after the `use` declaration.

~~~php
<?php
namespace Vendor\Package;

use Vendor\Package\FirstTrait;

class ClassName
{
    use FirstTrait;

    private $property;
}
~~~

### 4.3 Properties

Visibility MUST be declared on all properties.

The `var` keyword MUST NOT be used to declare a property.

There MUST NOT be more than one property declared per statement.

Property names MUST NOT be prefixed with a single underscore to indicate
protected or private visibility.  That is, an underscore prefix explicitly has
no meaning.

A property declaration looks like the following.

~~~php
<?php
namespace Vendor\Package;

class ClassName
{
    public $foo = null;
}
~~~

### 4.4 Methods and Functions

Visibility MUST be declared on all methods.

Method names MUST NOT be prefixed with a single underscore to indicate
protected or private visibility.  That is, an underscore prefix explicitly has
no meaning.

Method and function names MUST NOT be declared with a space after the method name. The
opening brace MUST go on its own line, and the closing brace MUST go on the
next line following the body. There MUST NOT be a space after the opening
parenthesis, and there MUST NOT be a space before the closing parenthesis.

A method declaration looks like the following. Note the placement of
parentheses, commas, spaces, and braces:

~~~php
<?php
namespace Vendor\Package;

class ClassName
{
    public function fooBarBaz($arg1, &$arg2, $arg3 = [])
    {
        // method body
    }
}
~~~

A function declaration looks like the following. Note the placement of
parentheses, commas, spaces, and braces:

~~~php
<?php

function fooBarBaz($arg1, &$arg2, $arg3 = [])
{
    // function body
}
~~~

### 4.5 Method and function Arguments

In the argument list, there MUST NOT be a space before each comma, and there
MUST be one space after each comma.

Method and function arguments with default values MUST go at the end of the argument
list.

Method and function argument scalar type hints MUST be lowercase.

~~~php
<?php
namespace Vendor\Package;

class ClassName
{
    public function foo(int $arg1, &$arg2, $arg3 = [])
    {
        // method body
    }
}
~~~

Argument lists MAY be split across multiple lines, where each subsequent line
is indented once. When doing so, the first item in the list MUST be on the
next line, and there MUST be only one argument per line.

When the argument list is split across multiple lines, the closing parenthesis
and opening brace MUST be placed together on their own line with one space
between them.

~~~php
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
~~~

When you have a return type declaration present there MUST be one space after
the colon with followed by the type declaration. The colon and declaration MUST be
on the same line as the argument list closing parentheses with no spaces between
the two characters. The declaration keyword (e.g. string) MUST be lowercase.

~~~php
<?php
declare(strict_types=1);

namespace Vendor\Package;

class ReturnTypeVariations
{
    public function functionName($arg1, $arg2): string
    {
        return 'foo';
    }
}
~~~

### 4.6 `abstract`, `final`, and `static`

When present, the `abstract` and `final` declarations MUST precede the
visibility declaration.

When present, the `static` declaration MUST come after the visibility
declaration.

~~~php
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
~~~

### 4.7 Method and Function Calls

When making a method or function call, there MUST NOT be a space between the
method or function name and the opening parenthesis, there MUST NOT be a space
after the opening parenthesis, and there MUST NOT be a space before the
closing parenthesis. In the argument list, there MUST NOT be a space before
each comma, and there MUST be one space after each comma.

~~~php
<?php

bar();
$foo->bar($arg1);
Foo::bar($arg2, $arg3);
~~~

Argument lists MAY be split across multiple lines, where each subsequent line
is indented once. When doing so, the first item in the list MUST be on the
next line, and there MUST be only one argument per line. A single argument being
split across multiple lines (as might be the case with an anonymous function or
array) does not constitute splitting the argument list itself.

~~~php
<?php

$foo->bar(
    $longArgument,
    $longerArgument,
    $muchLongerArgument
);
~~~

~~~php
<?php

somefunction($foo, $bar, [
  // ...
], $baz);

$app->get('/hello/{name}', function ($name) use ($app) {
    return 'Hello ' . $app->escape($name);
});
~~~

5. Control Structures
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


### 5.1 `if`, `elseif`, `else`

An `if` structure looks like the following. Note the placement of parentheses,
spaces, and braces; and that `else` and `elseif` are on the same line as the
closing brace from the earlier body.

~~~php
<?php

if ($expr1) {
    // if body
} elseif ($expr2) {
    // elseif body
} else {
    // else body;
}
~~~

The keyword `elseif` SHOULD be used instead of `else if` so that all control
keywords look like single words.


### 5.2 `switch`, `case`

A `switch` structure looks like the following. Note the placement of
parentheses, spaces, and braces. The `case` statement MUST be indented once
from `switch`, and the `break` keyword (or other terminating keyword) MUST be
indented at the same level as the `case` body. There MUST be a comment such as
`// no break` when fall-through is intentional in a non-empty `case` body.

~~~php
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
~~~


### 5.3 `while`, `do while`

A `while` statement looks like the following. Note the placement of
parentheses, spaces, and braces.

~~~php
<?php

while ($expr) {
    // structure body
}
~~~

Similarly, a `do while` statement looks like the following. Note the placement
of parentheses, spaces, and braces.

~~~php
<?php

do {
    // structure body;
} while ($expr);
~~~

### 5.4 `for`

A `for` statement looks like the following. Note the placement of parentheses,
spaces, and braces.

~~~php
<?php

for ($i = 0; $i < 10; $i++) {
    // for body
}
~~~

### 5.5 `foreach`

A `foreach` statement looks like the following. Note the placement of
parentheses, spaces, and braces.

~~~php
<?php

foreach ($iterable as $key => $value) {
    // foreach body
}
~~~

### 5.6 `try`, `catch`, `finally`

A `try-catch-finally` block looks like the following. Note the placement of
parentheses, spaces, and braces.

~~~php
<?php

try {
    // try body
} catch (FirstThrowableType $e) {
    // catch body
} catch (OtherThrowableType $e) {
    // catch body
} finally {
    // finally body
}
~~~

6. Operators
-----------
All binary and ternary (but not unary) operators MUST be preceded and followed by at least
one space. This includes all [arithmetic][], [comparison][], [assignment][], [bitwise][],
[logical][] (excluding `!` which is unary), [string concatenation][], and [type][] operators.

Other operators are left undefined.

For example:

~~~php
<?php

if ($a === $b) {
    $foo = $bar ?? $a ?? $b;
} elseif ($a > $b) {
    $variable = $foo ? 'foo' : 'bar';
}
~~~

7. Closures
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

~~~php
<?php

$closureWithArgs = function ($arg1, $arg2) {
    // body
};

$closureWithArgsAndVars = function ($arg1, $arg2) use ($var1, $var2) {
    // body
};
~~~

Argument lists and variable lists MAY be split across multiple lines, where
each subsequent line is indented once. When doing so, the first item in the
list MUST be on the next line, and there MUST be only one argument or variable
per line.

When the ending list (whether of arguments or variables) is split across
multiple lines, the closing parenthesis and opening brace MUST be placed
together on their own line with one space between them.

The following are examples of closures with and without argument lists and
variable lists split across multiple lines.

~~~php
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
~~~

Note that the formatting rules also apply when the closure is used directly
in a function or method call as an argument.

~~~php
<?php

$foo->bar(
    $arg1,
    function ($arg2) use ($var1) {
        // body
    },
    $arg3
);
~~~

8. Anonymous Classes
--------------------

Anonymous Classes MUST follow the same guidelines and principles as closures
in the above section.


~~~php
<?php

$instance = new class {};
~~~

The opening parenthesis MAY be on the same line as the `class` keyword so long as
the list of `implements` interfaces does not wrap. If the list of interfaces
wraps, the parenthesis MUST be placed on the line immediately following the last
interface.

~~~php
<?php

// Parenthesis on the same line
$instance = new class extends \Foo implements \HandleableInterface {
    // Class content
};

// Parenthesis on the next line
$instance = new class extends \Foo implements
    \ArrayAccess,
    \Countable,
    \Serializable
{
    // Class content
};
~~~

[PSR-1]: http://www.php-fig.org/psr/psr-1/
[PSR-2]: http://www.php-fig.org/psr/psr-2/
[keywords]: http://php.net/manual/en/reserved.keywords.php
[arithmetic]: http://php.net/manual/en/language.operators.arithmetic.php
[assignment]: http://php.net/manual/en/language.operators.assignment.php
[comparison]: http://php.net/manual/en/language.operators.comparison.php
[bitwise]: http://php.net/manual/en/language.operators.bitwise.php
[logical]: http://php.net/manual/en/language.operators.logical.php
[string concatenation]: http://php.net/manual/en/language.operators.string.php
[type]: http://php.net/manual/en/language.operators.type.php
