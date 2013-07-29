PSR-X: Autoloader
=================

The key words "MUST", "MUST NOT", "REQUIRED", "SHALL", "SHALL NOT", "SHOULD",
"SHOULD NOT", "RECOMMENDED", "MAY", and "OPTIONAL" in this document are to be
interpreted as described in [RFC 2119](http://tools.ietf.org/html/rfc2119).


1. Overview
-----------

This PSR specifies the rules for an interoperable PHP autoloader that can
co-exist with any other SPL registered autoloaders, which via some means of
configuration will map valid PHP namespaces to valid "base directories" in the
file system.


2. Definitions
--------------

- **class**: The term _class_ refers to PHP classes, interfaces, and traits.

- **fully qualified class name**: The full namespace and class name, with
  leading backslash. (This is per the
  [Name Resolution Rules](http://php.net/manual/en/language.namespaces.rules.php)
  from the PHP
  manual.)

- **namespace**: Given a _fully qualified class name_ of `\Foo\Bar\Baz\Qux`,
  the _namespace_ is `\Foo\Bar\Baz\`.

- **namespace names**: Given a _fully qualified class name_ of
  `\Foo\Bar\Baz\Qux`, the _namespace names_ are `Foo`, `Bar`, and `Baz`.

- **namespace prefix**: One or more contiguous _namespace names_ at the start
  of the _namespace_. Given a _fully qualified class name_ of
  `\Foo\Bar\Baz\Qux`, the _namespace prefix_ may be `\Foo\`, `\Foo\Bar\`, or
  `\Foo\Bar\Baz\`.

- **relative class name**: The parts of the _fully qualified class name_ that
  appear after the _namespace prefix_. Given a _fully qualified class name_ of
  `\Foo\Bar\Baz\Qux` and a _namespace prefix_ of `\Foo\Bar\`, the _relative
  class name_ is `Baz\Qux`.

- **base directory**: The directory path in the file system where the files for
  _relative class names_ have their root. Given a namespace prefix of 
  `\Foo\Bar\`, the _base directory_ could be `/path/to/packages/foo-bar/src`.

- **mapped file name**: The path in the file system resulting from the
  transformation of a _fully qualified class name_. Given a _fully qualified
  class name_ of `\Foo\Bar\Baz\Qux`, a namespace prefix of `\Foo\Bar\`, and a
  _base directory_ of `/path/to/packages/foo-bar/src`, the transformation
  rules in the specification will result in a _mapped file name_ of
  `/path/to/packages/foo-bar/src/Baz/Qux.php`.


3. Specification
----------------

- A fully qualified class name MUST begin with a top-level namespace name,
  which MUST be followed by zero or more sub-namespace names, and MUST end in
  a class name.

- The namespace prefix of a fully qualified class name MUST be mapped to a
  base directory; that namespace prefix MAY be mapped to more than one base
  directory.

- The class name MUST be transformed into a file path using PSR-T. The class
  name MUST be used as the logical path, a namespace prefix MUST be used
  as the logical prefix, the logical separator MUST be a backslash, the
  related base directory for the namespace prefix MUST be used as the
  directory prefix. The transformed path MUST be suffixed with `.php`.

- If the transformed path exists in the file system, the registered autoloader
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


### Example: Project-Specific Implementation

The following is one possible project-specific implementation of the
specification.

```php
<?php
// if this closure is registered in a file at /path/to/project/autoload.php ...
spl_autoload_register(function ($class) {
    // PSR-T: normalize the project namespace prefix
    $prefix = '\\Foo\\Bar\\';
    
    // PSR-T: normalize the class name
    $class = `\\` . ltrim($class, '\\');
    
    // PSR-T: normalize the directory prefix
    $dir_prefix = __DIR__ . '/src/';
    
    // PSR-T: validate the namespace prefix
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) === 0) {
        return;
    }
    
    // PSR-T: get the filename relative to the namespace path
    $relative = substr($class, $len);
    
    // PSR-T: build the path to the file containing the class
    $file = $dir_prefix . str_replace('\\', '/', $relative);
    
    // PSR-X: find the file
    $file .= '.php';
    if (is_readable($file)) {
        include $file;
    }

});

// ... then the following line would cause the autoloader to attempt to load
// the \Foo\Bar\Baz\Qux class from /path/to/project/src/Baz/Qux.php
new \Foo\Bar\Baz\Qux;
?>
```


### Example: General-Purpose Implementation

The following is one possible general-purpose implementation of the
specification.

```php
<?php
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
     * @param string $dir_prefix A base directory for class files in the namespace.
     * 
     * @param bool $prepend If true, prepend the base directory to the stack
     * instead of appending it; this causes it to be searched first rather
     * than last.
     */
    public function addNamespace($prefix, $dir_prefix, $prepend = false)
    {
        // PSR-T: normalize logical prefix with leading and trailing logical
        // separators. two steps so that we don't destroy a single separator.
        $prefix = '\\' . ltrim($prefix, '\\');
        $prefix = rtrim($prefix, '\\') . '\\';
        
        // PSR-T: normalize the directory prefix
        $dir_prefix = rtrim($dir_prefix, DIRECTORY_SEPARATOR)
                    . DIRECTORY_SEPARATOR;
        
        // initialize the prefix array
        if (isset($this->prefixes[$prefix]) === false) {
            $this->prefixes[$prefix] = array();
        }
        
        // retain the directory for the prefix
        if ($prepend) {
            array_unshift($this->prefixes[$prefix], $dir_prefix);
        } else {
            array_push($this->prefixes[$prefix], $dir_prefix);
        }
    }

    /**
     * Loads the class file for a given class name.
     *
     * @param string $class The fully-qualified class name.
     */
    public function loadClass($class)
    {
        // PSR-T: normalize a leading backslash if not already present
        $class = '\\' . ltrim($class, '\\');
        
        // work backwards through the segments of the fully-qualified class
        // name to find a class file
        $prefix = $class;
        $suffix = '';
        while ($prefix != '\\') {
            
            // look for the next rightmost namespace separator
            $pos = strrpos($prefix, '\\', -2);
            
            // get the new prefix
            $prefix = '\\' . substr($class, 1, $pos);
            
            // are there any base directories for this prefix?
            if (isset($this->prefixes[$prefix]) === false) {
                continue;
            }
            
            // PSR-T: get the suffix, convert namespace separators to
            // directory separators
            $suffix = substr($class, $pos + 1);
            $suffix = str_replace('\\', DIRECTORY_SEPARATOR, $suffix);
            
            // PSR-X: append .php to the suffix
            $suffix .= '.php';
            
            // look through base directories for this namespace prefix
            foreach ($this->prefixes[$prefix] as $dir_prefix) {
            
                // PSR-T: finish the transformation
                $file = $dir_prefix . $suffix;
                
                // PSR-X: can we read the file from the file system?
                if (is_readable($file)) {
                    // yes, we're done
                    include $file;
                }
            }
        }
    }
}
?>
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
?>
```
