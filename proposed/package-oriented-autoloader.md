PSR-X: Package-Oriented Autoloader
==================================

The key words "MUST", "MUST NOT", "REQUIRED", "SHALL", "SHALL NOT", "SHOULD", "SHOULD NOT", "RECOMMENDED", "MAY", and "OPTIONAL" in this document are to be interpreted as described in [RFC 2119](http://tools.ietf.org/html/rfc2119).


1. Overview
-----------

This PSR is intended as an alternative to, not a replacement for, [PSR-0](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-0.md). It allows for classes to map to directory structures like the following:

    /path/to/packages/foo/bar/
        src/
            Baz.php             # Foo\Bar\Baz
            Dib/
                Zim.php         # Foo\Bar\Dib\Zim
        tests/
            BazTest.php         # Foo\Bar\BazTest
            Dib/
                ZimTest.php     # Foo\Bar\Dib\ZimTest.php


2. Definitions
--------------

- `class`: The term "class" refers to PHP classes, interfaces, and traits.

- `fully-qualified class name`: An absolute namespace and class name; e.g., `Foo\Bar\Dib\Zim`.  The `namespace portion` is `Foo\Bar\Dib` and the `class portion` is `Zim`.

- `namespace prefix`: One or more parts of the namespace portion of the fully qualified class name.  Given a FQCN of `Foo\Bar\Dib\Zim`, the namespace prefix may be `Foo`, `Foo\Bar`, or `Foo\Bar\Dib`.

- `non-namespace prefix`: The parts of the FQCN that appear after the namesspace prefix.  Given a FQCN of `Foo\Bar\Dib\Zim` and a namespace prefix of `Foo\Bar`, the non-namespace prefix portion is `Dib\Zim`.


3. Specification
----------------

- Class files MUST contain only one class definition.

- Fully-qualified class names MUST begin with a top-level namespace name, which MUST be followed by zero or more sub-namespace names, and MUST end in a class name.

- Each namespace prefix portion of fully-qualified class names MUST be mapped to a base directory; that namespace prefix MAY be mapped to more than one base directory.

- The non-namespace prefix portion of a fully-qualified class name MUST be mapped to a sub-path by replacing namespace separators with directory separators, and the result MUST be suffixed with `.php`.


4. Narrative
------------

Given the below example implementation, and a `foo/bar` package of classes, one would register the path to "source" files and "unit test" files for the `Foo\Bar` namespace prefix like so:

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

5. Example Implementation
-------------------------

The following is one possible implementation of above specification.

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
