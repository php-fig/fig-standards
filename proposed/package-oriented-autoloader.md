PSR-X: Package-Oriented Autoloader
==================================

The key words "MUST", "MUST NOT", "REQUIRED", "SHALL", "SHALL NOT", "SHOULD",
"SHOULD NOT", "RECOMMENDED", "MAY", and "OPTIONAL" in this document are to be
interpreted as described in [RFC 2119](http://tools.ietf.org/html/rfc2119).


1. Overview
-----------

This PSR is intended as an alternative to, not a replacement for,
[PSR-0](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-0.md).
It removes some legacy compatibility features that were built into PSR-0, in
particular the handling of underscores in class names, and it allows for
classes to map to shallower directory structures.


2. Definitions
--------------

- `class`: The term "class" refers to PHP classes, interfaces, and traits.

- `absolute class name`: A fully-qualified namespace and class name.

- `namespace`: Given an `absolute class name` of `Foo\Bar\Dib\Zim`, the
  `namespace` is `Foo\Bar\Dib`.

- `namespace name`: Given an `absolute class name` of `Foo\Bar\Dib\Zim`, the
  `namespace names` are `Foo`, `Bar`, and `Dib`.

- `namespace prefix`: One or more contiguous `namespace names` at the start of
  the `namespace`. Given an `absolute class name` of `Foo\Bar\Dib\Zim`, the
  `namespace prefix` may be `Foo`, `Foo\Bar`, or `Foo\Bar\Dib`.

- `relative class name`: The parts of the `absolute class name` that appear
  after the `namespace prefix`. Given an `absolute class name` of
  `Foo\Bar\Dib\Zim` and a `namespace prefix` of `Foo\Bar`, the `relative class
  name` is `Dib\Zim`.

- `base directory`: The absolute directory path on disk where the files for
  `relative class names` have their root.


3. Specification
----------------

- Each class file MUST contain only one class definition.

- Each absolute class name MUST begin with a top-level namespace name, which
  MUST be followed by zero or more sub-namespace names, and MUST end in a
  class name.

- Each namespace prefix of asbolute class names MUST be mapped to a base
  directory; that namespace prefix MAY be mapped to more than one base
  directory.

- Each relative class name MUST be mapped to a sub-path by replacing namespace
  separators with directory separators, and the result MUST be suffixed with
  `.php`.


4. Example Implementations
--------------------------

The example implementations MUST NOT be regarded as part of the specification;
they are examples only. Class loaders MAY contain additional features and MAY
differ in how they are implemented. As long as a class loader adheres to the
rules set forth in the specification they MUST be considered compatible
with this PSR.


### Example: Project-Specific Implementation

The following is one possible project-specific implementation of the
specification.

```php
<?php
// if this closure is registered in a file at /path/to/project/autoload.php ...
spl_autoload_register(function ($absoluteClass) {
    $namespacePrefix = 'Foo\Bar';
    $baseDirectory = __DIR__ . '/src/';
    if (0 === strncmp($namespacePrefix, $absoluteClass, strlen($namespacePrefix))) {
        $relativeClass = substr($absoluteClass, strlen($namespacePrefix));
        $relativeFile = str_replace('\\', '/', $relativeClass) . '.php';
        $path = $baseDirectory . $relativeFile;
        if (is_readable($path)) {
            require $path;
            return true;
        }
    }
    return false;
});

// ... then the following line would cause the autoloader to attempt to load
// the \Foo\Bar\Dib\Zim class from /path/to/project/src/Dib/Zim.php
new \Foo\Bar\Dib\Zim;
```


### Example: General-Purpose Implementation

The following is one possible general-purpose implementation of the
specification.

```php
<?php
namespace Psr;

/**
 * 
 * An example implementation for a package-oriented autoloader. Note that this
 * is only an example, and is not a specification in itself.
 * 
 */
class PackageOrientedAutoloader
{
    /**
     * 
     * An array where the key is a namespace prefix, and the value is a
     * a base directory for classes in that namespace.
     * 
     * @var array
     * 
     */
    protected $prefix_base = array();

    /**
     * 
     * Sets the directory for a namespace prefix.
     * 
     * @param string $prefix The namespace prefix.
     * 
     * @param string $dir The directory containing classes in that
     * namespace.
     * 
     * @return void
     * 
     */
    public function setNamespacePrefixBase($prefix, $base)
    {
        $base = rtrim($base, DIRECTORY_SEPARATOR);
        $this->prefix_base[$prefix] = $base;
    }

    /**
     * 
     * Loads the class file for an absolute class name.
     * 
     * @param string $absolute The absolute class name.
     * 
     */
    public function load($absolute)
    {
        // a partial relative file name
        $relative = '';

        // go through the individual names in the absolute class name
        $names = explode('\\', $absolute);
        while ($names) {

            // take the last element off the absolute class name, and add to
            // the relative class name, representing a partial file name.
            $relative = $relative . DIRECTORY_SEPARATOR . array_pop($names);

            // the remaining elements indicate the namespace prefix
            $prefix = implode('\\', $names);

            // is there a base for this namespace prefix?
            if (isset($this->prefix_base[$prefix])) {
                
                // yes. create a complete file name from the namespace dir
                // and partial name
                $file = $this->prefix_base[$prefix] . $relative . '.php';

                // can we read the file from the filesystem?
                if ($this->readFile($file)) {
                    // done!
                    return true;
                }
            }
        }

        // never found a file for the class
        return false;
    }
    
    /**
     * 
     * Uses `require` to read a file from the filesystem.
     * 
     * @param string $file
     * 
     * @return bool True if the file gets read; false if it does not.
     * 
     */
    protected function readFile($file)
    {
        if (! is_readable($file)) {
            return false;
        }

        require $file;
        return true;
    }
}
```

Given the example general-purpose implementation, and a `foo/bar` package of
classes on disk at the following paths ...

    /path/to/packages/foo/bar/
        src/
            Baz.php             # Foo\Bar\Baz
            Dib/
                Zim.php         # Foo\Bar\Dib\Zim
        tests/
            BazTest.php         # Foo\Bar\BazTest
            Dib/
                ZimTest.php     # Foo\Bar\Dib\ZimTest.php

... one would register the path to "source" files and "unit test" files for
the `Foo\Bar` namespace prefix like so:

```php
<?php
// instantiate the loader
$loader = new PackageOrientedLoader;

// register the source file paths for the namespace prefix
$loader->addNamespacePath(
    'Foo\Bar',
    '/path/to/packages/foo/bar/src'
);

// also register the unit test paths for the namespace prefix
$loader->addNamespacePath(
    'Foo\Bar',
    '/path/to/packages/foo/bar/tests'
);
```
