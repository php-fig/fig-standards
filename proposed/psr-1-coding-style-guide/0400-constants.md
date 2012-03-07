Constants
=========

The PHP keywords `true`, `false`, and `null` are all lower case all the time.

All other user-defined constants are all upper case all the time, with
underscore separators.

    <?php
    namespace Vendor\Package;
    class ClassName
    {
        const SOME_CONSTANT_NAME = 'constant value';
    }
    
Global constants are discouraged; when present, they must be prefixed with the
vendor and package name to deconflict with constants from other sources.

    define('VENDOR_PACKAGE_CONSTANT_NAME', 'constant value');

