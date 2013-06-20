PSR-X: Autoloader
=================

The key words "MUST", "MUST NOT", "REQUIRED", "SHALL", "SHALL NOT", "SHOULD",
"SHOULD NOT", "RECOMMENDED", "MAY", and "OPTIONAL" in this document are to be
interpreted as described in [RFC 2119](http://tools.ietf.org/html/rfc2119).


1. Overview
-----------

This PSR specifies the rules for an interoperable autoloader.


2. Definitions
--------------

- `class`: The term "class" refers to PHP classes, interfaces, and traits.

- `fully qualified class name`: The full namespace and class name, with
  leading backslash. (This is per the
  [Name Resolution Rules](http://php.net/manual/en/language.namespaces.rules.php)
  from the PHP manual.)

- `namespace`: Given a `fully qualified class name` of `\Foo\Bar\Baz\Qux`, the
  `namespace` is `\Foo\Bar\Baz\`.

- `namespace name`: Given a `fully qualified class name` of
  `\Foo\Bar\Baz\Qux`, the `namespace names` are `Foo`, `Bar`, and `Baz`.

- `namespace prefix`: One or more contiguous `namespace names` at the start of
  the `namespace`. Given a `fully qualified class name` of `\Foo\Bar\Baz\Qux`,
  the `namespace prefix` may be `\Foo\`, `\Foo\Bar\`, or `\Foo\Bar\Baz\`.

- `relative class name`: The parts of the `fully qualified class name` that
  appear after the `namespace prefix`. Given a `fully qualified class name` of
  `\Foo\Bar\Baz\Qux` and a `namespace prefix` of `\Foo\Bar\`, the `relative
  class name` is `Baz\Qux`.

- `base directory`: The directory path in the file system where the files for
  `relative class names` have their root.

- `mapped file name`: The path in the file system that must be included for a
  fully qualified class name. Given a fully qualified class name of
  `\Foo\Bar\Baz\Qux`, a namespace prefix of `\Foo\Bar\`, and a base directory
  of `/path/to/packages/foo-bar/src`, the translation rules set forth by the
  specification will result in a class file name of
  `/path/to/packages/foo-bar/src/Baz/Qux.php`.


3. Specification
----------------

- A fully qualified class name MUST begin with a top-level namespace name,
  which MUST be followed by zero or more sub-namespace names, and MUST end in
  a class name.

- The namespace prefix of a fully qualified class name MUST be mapped to a
  base directory; that namespace prefix MAY be mapped to more than one base
  directory.

- The relative class name MUST be mapped to a sub-path by replacing namespace
  separators with directory separators, and the result MUST be suffixed with
  `.php`; the resulting string MUST be appended to the base directory to form
  the mapped file name.

- If the mapped file name exists in the file system, the registered autoloader
  MUST include or require it.

- The registered autoloader callback MUST NOT throw exceptions, MUST NOT
  raise errors of any level, and SHOULD NOT return a value.


4. Example Implementations
--------------------------

The example implementations MUST NOT be regarded as part of the specification;
they are examples only. Class loaders MAY contain additional features and MAY
differ in how they are implemented. As long as a class loader adheres to the
rules set forth in the specification it MUST be considered compatible with
this PSR.

> N.b.: Before PHP 5.3.3, registered autoloaders would receive the fully
> qualified class name **with** the leading backslash in place; in PHP 5.3.3
> and later, they receive the fully qualified class name **without** the
> leading backslash. The example implementations strip leading backslashes
> to make allowance for that inconsistency, but any autoloader that wishes to
> exclusively target PHP 5.3.3 or greater can ignore this extra processing.


### Example: Project-Specific Implementation

The following is one possible project-specific implementation of the
specification.

```php
<?php
// if this closure is registered in a file at /path/to/project/autoload.php ...
spl_autoload_register(function ($class) {
    // the project namespace prefix
    $prefix = 'Foo\\Bar\\';
    // remove any leading backslash
    $class = ltrim($class, '\\');
    // does the class match the namespace prefix?
    if (strncmp($prefix, $class, strlen($prefix)) === 0) {
        // filename relative to the namespace path
        $relative = substr($class, strlen($prefix));
        // build the path to the file containing the class
        $file = __DIR__ . '/src/' . str_replace('\\', '/', $relative) . '.php';
        if (is_readable($file)) {
            include $file;
        }
    }
});

// ... then the following line would cause the autoloader to attempt to load
// the \Foo\Bar\Baz\Qux class from /path/to/project/src/Baz/Qux.php
new \Foo\Bar\Baz\Qux;
```


### Example: General-Purpose Implementation

The following is one possible general-purpose implementation of the
specification.

```php
<?php
namespace Example;

/**
 * An example implementation of the above specification that includes the
 * optional functionality of allowing multiple base directories for a single
 * namespace prefix.
 *
 * Note that this is only an example, and is not a specification in itself.
 */
class ClassLoader
{
    /**
     * An associative array where the key is a namespace prefix and the value
     * is an array of base directories for classes in that namespace.
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
     * Adds a base directory for a namespace prefix.
     *
     * @param string $prefix The namespace prefix.
     * 
     * @param string $base A base directory for class files in the namespace.
     * 
     * @param bool $prepend If true, prepend the base directory to the stack
     * instead of appending it; this causes it to be searched first rather
     * than last.
     */
    public function addNamespace($prefix, $base, $prepend = false)
    {
        $prefix = trim($prefix, '\\');
        $base = rtrim($base, DIRECTORY_SEPARATOR);
        if ($prepend) {
            array_unshift($this->prefixes[$prefix], $base);
        } else {
            array_push($this->prefixes[$prefix], $base);
        }
    }

    /**
     * Loads the class file for a given class name.
     *
     * @param string $class The fully-qualified class name.
     */
    public function loadClass($class)
    {
        // remove any leading backslash
        $class = ltrim($class, '\\');
        
        // class file relative to the namespace base directory
        $relative = '';
        
        // go through the parts of the fully-qualified class name
        $parts = explode('\\', $class);
        while ($parts) {
            // append the last element of the fully-qualified class name
            // to the relative filename
            $relative .= DIRECTORY_SEPARATOR . array_pop($parts);
            
            // the remaining elements indicate the namespace prefix
            $prefix = implode('\\', $parts);
            
            // are there any base directories for this namespace prefix?
            if (isset($this->prefixes[$prefix]) === false) {
                // no
                continue;
            }
            
            // look through base directories for this namespace prefix
            foreach ($this->prefixes[$prefix] as $base) {
            
                // create a complete file name from the base directory and
                // relative file name
                $file = $base . $relative . '.php';
                
                // can we read the file from the file system?
                if (is_readable($file)) {
                    // yes, we're done
                    include $file;
                    return;
                }
            }
        }
    }
}
```

Given the example general-purpose implementation, and a `foo-bar` package of
classes in the file system at the following paths ...

    /path/to/packages/foo-bar/
        src/
            Baz.php             # Foo\Bar\Baz
            Qux/
                Quux.php        # Foo\Bar\Qux\Quux
        tests/
            BazTest.php         # Foo\Bar\BazTest
            Qux/
                QuuxTest.php    # Foo\Bar\Qux\QuuxTest

... add the path to the class files for the `\Foo\Bar\` namespace prefix
as follows:

```php
<?php
// instantiate the loader
$loader = new \Example\ClassLoader;

// register the autoloader
$loader->register();

// register the base directories for the namespace prefix
$loader->addNamespace('Foo\Bar', '/path/to/packages/foo-bar/src');
$loader->addNamespace('Foo\Bar', '/path/to/packages/foo-bar/tests');

// the following line would cause the autoloader to attempt to load
// the Foo\Bar\Baz\Qux class from /path/to/packages/foo-bar/src/Qux/Quux.php
new \Foo\Bar\Baz\Qux;

// the following line would cause the autoloader to attempt to load
// the Foo\Bar\Baz\Qux\QuuxTest class from /path/to/packages/foo-bar/tests/Qux/QuuxTest.php
new \Foo\Bar\Baz\Qux\QuuxTest;
```
