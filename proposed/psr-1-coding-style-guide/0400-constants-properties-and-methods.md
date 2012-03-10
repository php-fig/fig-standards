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
`$StudlyCase`, `$camelCase`, or `$under_score` property names. It
is often the case that variable names map directly field names in external
data sources. Changing between naming conventions when changing contexts
merely to suit a style guide would be counterproductive in such cases.

Some projects prefix property names with a single underscore to indicate
protected or private visibility. This guide discourages but does not disallow
that practice.

Whatever naming convention is used should be applied consistently within a
reasonable scope. That scope may be vendor-level, package-level, class-level,
or function-level.

Prefix all properties with a visibility declaration. The `static` declaration,
when present, goes before the visibility declaration.

    <?php
    namespace Vendor\Package;
    
    class ClassName
    {
        public $foo = null;
        static public $bar = null;
    }


Methods
-------

Declare method names in `camelCase()` with no space after the method name.

Some projects prefix function and method names with a single underscore to
indicate protected or private visibility. This guide discourages but does
not disallow that practice.

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
    
Prefix all methods with a visibility declaration. The `static` declaration,
when present, goes before the visibility declaration.

    <?php
    namespace Vendor\Package;
    
    class ClassName
    {
        static public function fooBarBaz($arg1, &$arg2, $arg3 = [])
        {
            // method body
        }
    }

Arguments with default values always go at the end of the argument list.

Argument lists that exceed the line length limit may be split across
subsequent indented lines. There is only one argument per line. The closing
parenthesis and opening brace are placed together on their own line.

    <?php
    namespace Vendor\Package;
    
    class ClassName
    {
        static public function fooBarBaz(
            ClassTypeHint $arg1,
            &$arg2,
            array $arg3 = []
        ) {
            // method body
        }
    }
