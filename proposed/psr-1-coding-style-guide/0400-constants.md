Constants
=========

The PHP keywords `true`, `false`, and `null` are all lower case all the time.

All other user-defined constants are all upper case all the time, with
underscore separators.

    <?php
    namespace Vendor\Package;
    
    class ClassName
    {
        const CONSTANT_NAME = 'constant value';
    }
    
Global constants are strongly discouraged. Instead, create namespaced
constants for the vendor and package:

    <?php
    namespace Vendor\Package;
    const CONSTANT_NAME = 'constant_value';
    
If a global constant is absolutely unavoidable, prefix it with the vendor and
package name:

    <?php
    define('VENDOR_PACKAGE_CONSTANT_NAME', 'constant_value');

