`namespace`, `use`, and `class`
===============================

All classes should be named with PSR-0 in mind. This means each class should
be in a file by itself, and should be in a namespace of at least two levels: a
top-level vendor name, and a second-level package name within that vendor.

Abstract classes should be prefixed with `Abstract`.

Interfaces should be suffixed with `Interface`.

Traits should be suffixed with `Trait`.

Class names are always in `StudlyCase`. The class declaration should have one
empty line above it. The opening and closing braces for the class go on their
own line.

    <?php
    namespace Vendor\Package;
    
    class ClassName
    {
        // constants, properties, methods
    }

Lists of `use` should go immediately after then namespace declaration with no
line between them. There should be only one `use` keyword; each line should
list only one class, and should be indented one level.

    <?php
    namespace Vendor\Package;
    use FooClass,
        BarClass as Bar,
        BazClass;
    
    class ClassName
    {
        // constants, properties, methods
    }

The `extends` and `implements` keywords should be on the same line as the
class name. Lists of `implements` that exceed the line length limit may be
split across multiple lines, where each subsequent line is indented once.
There should be only one interface listed per line.

    <?php
    namespace Vendor\Package;
    use FooClass,
        BarClass as Bar,
        BazClass;
    
    class ClassName extends ParentClass implements
        InterfaceName,
        AnotherInterfaceName,
        YetAnotherInterface,
        InterfaceInterface
    {
        // constants, properties, methods
    }

