`namespace`, `use`, and `class`
===============================

All namespaces and classes should be named with PSR-0 in mind. This means each
class should be in a file by itself, and should be in a namespace of at least
two levels: a top-level vendor name, and a second-level package name within
that vendor.

Class names are always in `StudlyCase`. The class declaration should have one
empty line above it. The opening and closing braces for the class go on their
own line.

    <?php
    namespace Vendor\Package;
    
    class ClassName
    {
        // constants, properties, methods
    }

The `use` declarations go immediately after the namespace declaration with no
line separating them. There should be one `use` keyword per declaration.

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
class name. Lists of `implements` that exceed the line length limit may be
split across multiple lines, where each subsequent line is indented once.
List only one interface per line.

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

