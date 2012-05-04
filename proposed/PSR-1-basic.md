Basic Coding Standard
=====================


Overview
--------

- Use only <?php and <?= tags.

- Use only UTF-8 (no BOM) for PHP code.

- Declare class names in `StudlyCaps`.

- Declare class constants in all upper case with underscore separators.

- Declare method names in `camelCase`.


General
-------

### PHP Tags

Use the long `<?php ?>` tags for PHP code. Use of short-echo `<?= ?>` tags is
also allowed. Do not use the other tag variations.

### Character Encoding

Use only UTF-8 (no BOM) for PHP code. Do not use other character encodings.


`namespace` and `class`
-----------------------

> N.b.: Formal namespaces were introduced in PHP 5.3. Code written for 5.2.x
> and before must use the pseudo-namespacing convention of `Vendor_`
> prefixes on class names. Code written for PHP 5.3 and after must use
> formal namespaces.

All namespaces and classes are to be named with [PSR-0][] in mind. This means
each class is in a file by itself, and is in a namespace of at least one
level: a top-level vendor name.

[PSR-0]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-0.md

Declare class names in `StudlyCaps`.


Class Constants, Properties, and Methods
----------------------------------------

> N.b.: The term "class" refers to all classes, interfaces, and traits.

### Constants

Declare class in all upper case with underscore separators; e.g.,
`CONSTANT_NAME`.

### Properties

This guide expressly avoids any recommendation regarding the use of
`$StudlyCaps`, `$camelCase`, or `$under_score` property names.

Some projects prefix property names with a single underscore to indicate
protected or private visibility; this guide discourages but does not disallow
that practice.

Whatever naming convention is used must be applied consistently within a
reasonable scope. That scope may be vendor-level, package-level, class-level,
or method-level.

### Methods

Declare method names in `camelCase()`.
