Basic Coding Standard
=====================

This section of the standard comprises what should be considered the mandatory
styling elements that are required to ensure a high level of technical
interoperability between shared PHP code.

1. Overview
-----------

- Use only <?php and <?= tags.

- Use only UTF-8 (no BOM) for PHP code.

- Declare class names in `StudlyCaps`.

- Declare class constants in all upper case with underscore separators.

- Declare method names in `camelCase`.


2. Files
--------

### 2.1. PHP Tags

Use the long `<?php ?>` tags for PHP code. Use of short-echo `<?= ?>` tags is
also allowed. Do not use the other tag variations.

### 2.2. Character Encoding

Use only UTF-8 (no BOM) for PHP code. Do not use other character encodings.

### 2.3. Side Effects

A file should *either* declare new symbols (classes, functions, constants,
etc.) and have no other side effects, *or* it should execute logic with side
effects, but *not* both.

The phrase "side effects" means execution of logic not directly related to
declaring classes, functions, constants, etc., *merely from including the file*.

"Side effects" include but are not limited to: generating output, explicit
use of `require` or `include`, connecting to external services, modifying ini
settings, emitting errors or exceptions, and so on.

An example of a file with "side effects":

```php
<?php
// side effect: change ini settings
ini_set('error_reporting', E_ALL);

// side effect: loads a file
include "file.php";

// declaration (not a side effect)
function foo()
{
    // function body
}
```

An example of a file with no side effects:

```php
<?php
// conditional declaration is *not* a side effect
if (! function_exists('foo')) {
    function foo()
    {
        // function body
    }
}

// declaration
function bar()
{
    // function body
}
```


3. Namespace and Class Names
----------------------------

All namespaces and classes are to be named with [PSR-0][] in mind. This means
each class is in a file by itself, and is in a namespace of at least one
level: a top-level vendor name.

[PSR-0]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-0.md

Declare class names in `StudlyCaps`.

For example:

```php
<?php
// PHP 5.3 and later:
namespace Vendor\Model;

class Foo
{
}
```

Formal namespaces were introduced in PHP 5.3. Code written for 5.2.x
and before must use the pseudo-namespacing convention of `Vendor_`
prefixes on class names. Code written for PHP 5.3 and after must use
formal namespaces.

```php
<?php
// PHP 5.2.x and earlier:
class Vendor_Model_Foo
{
}
```

4. Class Constants, Properties, and Methods
-------------------------------------------

The term "class" refers to all classes, interfaces, and traits.


### 4.1. Constants

Declare class in all upper case with underscore separators. For example:

```php
<?php
namespace Vendor\Model;

class Foo
{
    const VERSION;
    const DATE_APPROVED;
}
```

### 4.2. Properties

This guide expressly avoids any recommendation regarding the use of
`$StudlyCaps`, `$camelCase`, or `$under_score` property names.

Some projects prefix property names with a single underscore to indicate
protected or private visibility; this guide discourages but does not disallow
that practice.

Whatever naming convention is used must be applied consistently within a
reasonable scope. That scope may be vendor-level, package-level, class-level,
or method-level.

### 4.3. Methods

Declare method names in `camelCase()`.
