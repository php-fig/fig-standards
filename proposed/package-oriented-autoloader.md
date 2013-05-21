PSR-4: Class Autoloader
=======================

The key words "MUST", "MUST NOT", "REQUIRED", "SHALL", "SHALL NOT", "SHOULD",
"SHOULD NOT", "RECOMMENDED", "MAY", and "OPTIONAL" in this document are to be
interpreted as described in [RFC 2119](http://tools.ietf.org/html/rfc2119).


1. Overview
-----------

This PSR specifies the rules for an interoperable autoloader. It is intended
as a co-existing alternative to, not a replacement for,
[PSR-0](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-0.md).
It removes some legacy compatibility features that were built into PSR-0, in
particular the handling of underscores in class names, and it allows for
classes to map to shallower directory structures.


2. Definitions
--------------

- `class`: The term "class" refers to PHP classes, interfaces, and traits.

- `fully qualified class name`: A fully-qualified namespace and class name.

- `namespace`: Given a `fully qualified class name` of `Foo\Bar\Dib\Zim`, the
  `namespace` is `Foo\Bar\Dib`.

- `namespace name`: Given an `fully qualified class name` of `Foo\Bar\Dib\Zim`, the
  `namespace names` are `Foo`, `Bar`, and `Dib`.

- `namespace prefix`: One or more contiguous `namespace names` at the start of
  the `namespace`. Given a `fully qualified class name` of `Foo\Bar\Dib\Zim`, the
  `namespace prefix` may be `Foo`, `Foo\Bar`, or `Foo\Bar\Dib`.

- `relative class name`: The parts of the `fully qualified class name` that appear
  after the `namespace prefix`. Given a `fully qualified class name` of
  `Foo\Bar\Dib\Zim` and a `namespace prefix` of `Foo\Bar`, the `relative class
  name` is `Dib\Zim`.

- `base directory`: The fully qualified directory path on disk where the files for
  `relative class names` have their root.


3. Specification
----------------

- A class file MUST contain only one class definition.

- A fully qualified class name MUST begin with a top-level namespace name, which
  MUST be followed by zero or more sub-namespace names, and MUST end in a
  class name.

- The namespace prefix of a fully qualified class name MUST be mapped to a base
  directory; that namespace prefix MAY be mapped to more than one base
  directory.

- The relative class name MUST be mapped to a sub-path by replacing namespace
  separators with directory separators, and the result MUST be suffixed with
  `.php`.


4. Example Implementations
--------------------------

The example implementations MUST NOT be regarded as part of the specification;
they are examples only. Class loaders MAY contain additional features and MAY
differ in how they are implemented. As long as a class loader adheres to the
rules set forth in the specification they MUST be considered compatible
with this PSR.

Please note functions registered for PHP autoloading  receive the fully
qualified class name with the leading backslash stripped, so `\Foo\Bar` is
received as `Foo\Bar`.


### Example: Project-Specific Implementation

The following is one possible project-specific implementation of the
specification.

```php
<?php
// this closure is registered in a file at /path/to/project/autoload.php ...
spl_autoload_register(function ($className) {
    $namespacePrefix = 'Foo\\Bar\\';
    if (0 === strncmp($namespacePrefix, $className, strlen($namespacePrefix))) {
        $relativeFile = substr($className, strlen($namespacePrefix));
        $file = __DIR__ . '/src/' . str_replace('\\', '/', $relativeFile) . '.php';
        if (is_readable($file)) {
            include $file;
        }
    }
});

// ... then the following line would cause the autoloader to attempt to load
// the Foo\Bar\Dib\Zim class from /path/to/project/src/Dib/Zim.php
new Foo\Bar\Dib\Zim;
```

### Example: General-Purpose Implementation

The following is one possible general-purpose implementation of the
specification.

```php
<?php
namespace Example;

/**
 * An example implementation for PSR-4 class autoloader.
 *
 * Note that this is only an example, and is not a specification
 * in itself.
 */
class ClassLoader
{
    /**
     * An associative array where the key is a namespace prefix
     * and the value is the base directory for classes in that namespace.
     *
     * @var array
     */
    protected $prefixes = array();

    /**
     * Register loader with SPL autoloader stack.
     */
    public function register()
    {
        spl_autoload_register(array($this, 'loadClass'));
    }

    /**
     * Sets the directory for a namespace prefix.
     *
     * @param string $prefix The namespace prefix.
     * @param string $path   The directory containing classes in that namespace.
     */
    public function setNamespacePrefix($prefix, $path)
    {
        $this->prefixes[$prefix] = rtrim($path, DIRECTORY_SEPARATOR);
    }

    /**
     * Loads the class file for a class name.
     *
     * @param string $className The fully-qualified class name.
     */
    public function loadClass($className)
    {
        // filename relative to the namespace prefix
        $relativeFile = '';

        $parts = explode('\\', $className);
        while ($parts) {
            // append the last element of the fully-qualified class name
            // to the relative filename
            $relativeFile .= DIRECTORY_SEPARATOR . array_pop($parts);

            // the remaining elements indicate the namespace prefix
            $prefix = implode('\\', $parts);

            // is there a base for this namespace prefix?
            if (isset($this->prefixes[$prefix])) {
                // build the path to the file containing the class
                $file = $this->prefixes[$prefix] . $relativeFile . '.php';
                if (is_readable($file)) {
                    include $file;
                    break;
                }
            }
        }
    }
}
```

Given the example general-purpose implementation, and a `foo-bar` package of
classes on disk at the following paths ...

    /path/to/packages/foo-bar/
        src/
            Baz.php             # Foo\Bar\Baz
            Dib/
                Zim.php         # Foo\Bar\Dib\Zim

... register the path to the class files for the `Foo\Bar\` namespace prefix
as follows:

```php
<?php
// instantiate the loader
$loader = new Example\ClassLoader;

// register the autoloader
$loader->register();

// register the base directory for the namespace prefix
$loader->setNamespacePrefix(
    'Foo\\Bar',
    '/path/to/packages/foo-bar/src'
);

// the following line would cause the autoloader to attempt to load
// the Foo\Bar\Dib\Zim class from /path/to/packages/foo-bar/src/Dib/Zim.php
new Foo\Bar\Dib\Zim;
```
