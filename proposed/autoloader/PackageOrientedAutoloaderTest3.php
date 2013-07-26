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
     * @param string $base A base directory for class files in the namespace.
     */
    public function addNamespace($prefix, $base)
    {
        $prefix = trim($prefix, '\\');
        $base = rtrim($base, DIRECTORY_SEPARATOR);
        $this->prefixes[$prefix][] = $base;
    }

    /**
     * Loads the class file for a class name.
     *
     * @param string $class The fully-qualified class name.
     */
    public function loadClass($class)
    {
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
            if (! isset($this->prefixes[$prefix])) {
                // no
                continue;
            }
            
            // look through base directories for this namespace prefix
            foreach ($this->prefixes[$prefix] as $base) {
            
                // create a complete file name from the base directory and
                // relative file name
                $file = $base . $relative . '.php';
                
                // can we read the file from the filesystem?
                if ($this->readFile($file)) {
                    // yes, we're done
                    return $file;
                }
            }
        }
        
        return false;
    }
    
    /**
     * 
     * Uses `include` to read a file from the filesystem.
     * 
     * @param string $file
     * @return bool True if the file gets read; false if it does not.
     * 
     */
    protected function readFile($file)
    {
        if (! is_readable($file)) {
            return false;
        }
        
        include $file;
        return true;
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
