`namespace`, `use`, and `class`
===============================

All namespaces and classes should be named with PSR-0 in mind. This means each
class should be in a file by itself, and should be in a namespace of at least
one level: a top-level vendor name.

The `namespace` line should have one blank line after it.

All `use` declarations go after the `namespace` declaration. There should be
one `use` keyword per declaration.

    <?php
    namespace Vendor\Package;
    
    use FooClass;
    use BarClass as Bar;
    use OtherVendor\OtherPackage\BazClass;

The `use` block should have one blank line after it.
    
Class names are always in `StudlyCase`. The opening and closing braces for the
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

The `extends` and `implements` keywords should be on the same line as the
class name.

Lists of `implements` that exceed the line length limit may be split across
multiple lines, where each subsequent line is indented once. List only one
interface per line.

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
