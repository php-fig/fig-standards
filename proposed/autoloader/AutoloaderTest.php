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
                if ($this->readFile($file)) {
                    // yes, we're done
                    return $file;
                }
            }
        }
        
        return false;
    }
}

class MockClassLoader extends ClassLoader
{
    protected $files = array();

    public function setFiles(array $files)
    {
        $this->files = $files;
    }

    protected function readFile($file)
    {
        return in_array($file, $this->files);
    }
}

class ClassLoaderTest extends \PHPUnit_Framework_TestCase
{
    protected $loader;

    protected function setUp()
    {
        $this->loader = new MockClassLoader;
    
        $this->loader->setFiles(array(
            '/vendor/foo.bar/src/ClassName.php',
            '/vendor/foo.bar/src/DoomClassName.php',
            '/vendor/foo.bar/tests/ClassNameTest.php',
            '/vendor/foo.bardoom/src/ClassName.php',
            '/vendor/foo.bar.baz.dib/src/ClassName.php',
            '/vendor/foo.bar.baz.dib.zim.gir/src/ClassName.php',
        ));
        
        $this->loader->addNamespace(
            'Foo\Bar',
            '/vendor/foo.bar/src'
        );
        
        $this->loader->addNamespace(
            'Foo\Bar',
            '/vendor/foo.bar/tests'
        );
        
        $this->loader->addNamespace(
            'Foo\BarDoom',
            '/vendor/foo.bardoom/src'
        );
        
        $this->loader->addNamespace(
            'Foo\Bar\Baz\Dib',
            '/vendor/foo.bar.baz.dib/src'
        );
        
        $this->loader->addNamespace(
            'Foo\Bar\Baz\Dib\Zim\Gir',
            '/vendor/foo.bar.baz.dib.zim.gir/src'
        );
    }

    public function testExistingFile()
    {
        $actual = $this->loader->loadClass('Foo\Bar\ClassName');
        $expect = '/vendor/foo.bar/src/ClassName.php';
        $this->assertSame($expect, $actual);
        
        $actual = $this->loader->loadClass('Foo\Bar\ClassNameTest');
        $expect = '/vendor/foo.bar/tests/ClassNameTest.php';
        $this->assertSame($expect, $actual);
    }
    
    public function testMissingFile()
    {
        $actual = $this->loader->loadClass('No_Vendor\No_Package\NoClass');
        $this->assertFalse($actual);
    }
    
    public function testDeepFile()
    {
        $actual = $this->loader->loadClass('Foo\Bar\Baz\Dib\Zim\Gir\ClassName');
        $expect = '/vendor/foo.bar.baz.dib.zim.gir/src/ClassName.php';
        $this->assertSame($expect, $actual);
    }
    
    public function testConfusion()
    {
        $actual = $this->loader->loadClass('Foo\Bar\DoomClassName');
        $expect = '/vendor/foo.bar/src/DoomClassName.php';
        $this->assertSame($expect, $actual);
        
        $actual = $this->loader->loadClass('Foo\BarDoom\ClassName');
        $expect = '/vendor/foo.bardoom/src/ClassName.php';
        $this->assertSame($expect, $actual);
    }
}
