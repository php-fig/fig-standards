Variable And Property Names
===========================

This guide expressly avoids any requirement regarding the use of
`$StudlyCase`, `$camelCase`, or `$under_score` variable and property names. It
is often the case that variable names map directly field names in external
data sources. Changing between naming conventions when changing contexts
merely to suit a style guide would be counterproductive in such cases.

Some projects prefix property names with a single underscore to indicate a
protected or private visibility. This guide discourages but does not disallow
that practice.

Whatever naming convention is used should be applied consistently within a
reasonable scope. That scope may be vendor-level, package-level, class-level,
or function-level.

Class properties should explicitly note the visibility keyword.

    <?php
    namespace Vendor\Package;
    
    class ClassName
    {
        public $property = null;
    }

Global variables are strongly discouraged. If a global variable is absolutely
unavoidable, it should be prefixed with the vendor and package name.

    <?php
    $Vendor_Package_VariableName = 'variable_value';
