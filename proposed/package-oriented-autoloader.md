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

- `fully-qualified class name (FQCN)`: An absolute namespace and class name.

- `namespace portion`: Given a `FQCN` of `Foo\Bar\Dib\Zim`, the `namespace
  portion` of the name is `Foo\Bar\Dib`.

- `class portion`: Given a `FQCN` of `Foo\Bar\Dib\Zim`, the `class portion` of
  the name is `Zim`.

- `namespace name`: An individual part of the `namespace portion` of the
  `FQCN`. Given a `FQCN` of `Foo\Bar\Dib\Zim`, the individual namespace names
  are `Foo`, `Bar`, and `Dib`.

- `namespace prefix`: One or more parts of the namespace portion of the fully
  qualified class name. Given a `FQCN` of `Foo\Bar\Dib\Zim`, the namespace
  prefix may be `Foo`, `Foo\Bar`, or `Foo\Bar\Dib`.

- `non-namespace prefix`: The parts of the `FQCN` that appear after the
  namespace prefix. Given a `FQCN` of `Foo\Bar\Dib\Zim` and a namespace prefix
  of `Foo\Bar`, the non-namespace prefix portion is `Dib\Zim`.

- `base directory`: The absolute directory path on disk where non-namespace
  prefix file names have their root.


3. Specification
----------------

- Class files MUST contain only one class definition.

- Fully-qualified class names MUST begin with a top-level namespace name,
  which MUST be followed by zero or more sub-namespace names, and MUST end in
  a class name.

- Each namespace prefix portion of fully-qualified class names MUST be mapped
  to a base directory; that namespace prefix MAY be mapped to more than one
  base directory.

- The non-namespace prefix portion of a fully-qualified class name MUST be
  mapped to a sub-path by replacing namespace separators with directory
  separators, and the result MUST be suffixed with `.php`.


4. Narrative
------------

Given the below example general-purpost implementation, and a `foo/bar`
package of classes on disk at the following paths ...

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

5. Example Implementations
--------------------------

The example implementations MUST NOT be regarded as part of the specification;
they are examples only. Class loaders MAY contain additional features and MAY
differ in how they are implemented. As long as a class loader adheres to the
rules set forth in the specification above they MUST be considered compatible
with this PSR.

### Example: General-Purpose Implementation

The following is one possible general-purpose implementation of the above
specification.


```php
<?php
/**
 * An example implementation for a package-oriented autoloader.
 */
class PackageOrientedAutoloader
{
    /**
     * 
     * An array where the key is a namespace prefix, and the value is a
     * sequential array of directories for classes in that namespace.
     * 
     * @var array
     * 
     */
    protected $paths = array();

    /**
     * 
     * Adds a path for a namespace prefix.
     * 
     * @param string $ns The namespace prefix.
     * 
     * @param string $path The directory containing classes in that
     * namespace.
     * 
     * @param bool $prepend Prepend (unshift) the path onto the list of 
     * paths instead of appending (pushing) it.
     * 
     * @return void
     * 
     */
    public function addNamespacePath($ns, $path, $prepend = false)
    {
        $path = rtrim($path, DIRECTORY_SEPARATOR);
        if ($prepend) {
            array_unshift($this->paths, $path);
        } else {
            $this->paths[$ns][] = $path;
        }
    }

    /**
     * 
     * Loads the class file for a fully qualified class name.
     * 
     * @param string $fqcn The fully-qualified class name.
     * 
     */
    public function load($fqcn)
    {
        // a partial file name for the class
        $name = '';

        // go through the parts of the fully-qualifed class name
        $parts = explode('\\', $fqcn);
        while ($parts) {

            // take the last element off the fully-qualified class name
            // and add to the partial file name. always have a leading
            // directory separator here.
            $name = $name . DIRECTORY_SEPARATOR . array_pop($parts);

            // the remaining parts indicate the registered namespace
            $ns = implode('\\', $parts);

            // is the namespace registered?
            if (! isset($this->paths[$ns])) {
                // no, continue the loop
                continue;
            }

            // the namespace is registered.  look through its paths.
            foreach ($this->paths[$ns] as $path) {

                // create a complete file name from the path
                // and partial name
                $file = $path . $name . '.php';

                // can we read the file from the filesystem?
                if ($this->readFile($file)) {
                    // yes, we're done
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


### Example: Project-Specific Implementation

The following is one possible project-specific implementation of the above
specification.

```php
<?php
// Given a PHP file named /path/to/project/example.php with the following
// content ...
spl_autoload_register(function ($fqcn) {
    $namespacePrefix = 'Foo\Bar';
    $baseDirectory = __DIR__.'/src/';
    if (0 === strncmp($namespacePrefix, $fqcn, strlen($namespacePrefix))) {
        $nonNamespacePrefix = substr($fqcn, strlen($namespacePrefix));
        $nonNamespaceFilename = str_replace('\\', '/', $nonNamespacePrefix).'.php';
        $path = $baseDirectory.$nonNamespaceFilename;
        if (file_exists($path)) {
            require $path;
            return true;
        }
    }
    return false;
});

// ... on calling the following line, the autolaoder registered above would
// attempt to load the FQCN from /path/to/project/src/Dib/Zim.php
new Foo\Bar\Dib\Zim;
```
