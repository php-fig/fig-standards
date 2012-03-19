`namespace`, `use`, and `class`
===============================

> N.b.: Formal mamespaces were introduced in PHP 5.3. Code written for 5.2.x
> and before should use the pseudo-namespacing convention of `Vendor_`
> prefixes on class names. Code written for PHP 5.3 and after should use
> formal namespaces.

All namespaces and classes are to be named with PSR-0 in mind. This means each
class should be in a file by itself, and should be in a namespace of at least
one level: a top-level vendor name.

The `namespace` line has one blank line after it.

All `use` declarations go after the `namespace` declaration. There is one
`use` keyword per declaration.

The `use` block has one blank line after it.
    
Class names are in `StudlyCaps`. The opening and closing braces for the
class go on their own line.

    <?php
    namespace Vendor\Package;
    
    use FooClass;
    use BarClass as Bar;
    use OtherVendor\OtherPackage\BazClass;

    class ClassName
    {
        // constants, properties, methods
    }

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
