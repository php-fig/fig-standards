Function And Method Declarations
================================

Function and method names are in `camelCase`.

Some projects prefix function and method names with a single underscore to
indicate a protected or private visibility. This guide discourages but does
not disallow that practice.


Functions
---------

A function declaration looks like the following. Note the placement of
parentheses, commas, spaces, and braces:

    <?php
    namespace Vendor\Package;
    
    function fooBarBaz($arg1, &$arg2, $arg3 = [], $arg4 = null)
    {
        // function body
    }

This guide encourages using static class methods rather than functions; doing
so helps to support autoloading.

Global functions are strongly discouraged. If a global function is
unavoidable, prefix it with the vendor and package name.

    <?php
    function Vendor_Package_FunctionName()
    {
        // function body
    }


Methods
-------

Class methods are always be prefixed with an explicit visibility keyword:

    <?php
    public function fooBarBaz($arg1, &$arg2, $arg3 = [], $arg4 = null)
    {
        // function body
    }
    
Static methods always have the `static` keyword before the visibility keyword:

    <?php
    static public function fooBarBaz($arg1, &$arg2, $arg3 = [], $arg4 = null)
    {
        // function body
    }


Arguments
---------

Arguments with default values always go at the end of the argument list.

Argument lists that exceed the line length limit may be split across
subsequent indented lines. There is only one argument per line. The closing
parenthesis and opening brace are placed together on their own line.

    <?php
    static public function fooBarBaz(
        ClassTypeHint $arg1,
        &$arg2,
        array $arg3 = [],
        $arg4 = null
    ) {
        // function body
    }

Anonymous Functions and Closures
--------------------------------

Declaration of anonymous functions looks like this; note the placement of
parentheses, commas, spaces and braces:

    $foo = function ($bar, $baz) use ($zim, $gir) {
        // body of the function
    };

This is a departure from the "normal" function and method declaration. Because
`function` is a keyword, it gets a space after it; the same is true for `use`.
The opening brace goes on the same line as the declaration; the body is
indented once; and the closing brace goes on its own outdented line.
