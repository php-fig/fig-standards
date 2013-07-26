<?php
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
        // the relative class name
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

class MockPackageOrientedAutoloader extends PackageOrientedAutoloader
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

class PackageOrientedAutoloaderTest extends PHPUnit_Framework_TestCase
{
    protected $loader;

    protected function setUp()
    {
        $this->loader = new MockPackageOrientedAutoloader;
    
        $this->loader->setFiles(array(
            '/vendor/foo.bar/src/ClassName.php',
            '/vendor/foo.bardoom/src/ClassName.php',
            '/vendor/foo.bar.baz.dib/src/ClassName.php',
            '/vendor/foo.bar.baz.dib.zim.gir/src/ClassName.php',
        ));
        
        $this->loader->setNamespacePrefixBase(
            'Foo\Bar',
            '/vendor/foo.bar/src'
        );
        
        $this->loader->setNamespacePrefixBase(
            'Foo\Bar',
            '/vendor/foo.bardoom/src'
        );
        
        $this->loader->setNamespacePrefixBase(
            'Foo\Bar\Baz\Dib',
            '/vendor/foo.bar.baz.dib/src'
        );
        
        $this->loader->setNamespacePrefixBase(
            'Foo\Bar\Baz\Dib\Zim\Gir',
            '/vendor/foo.bar.baz.dib.zim.gir/src'
        );
    }

    public function testExistingFile()
    {
        $actual = $this->loader->load('Foo\Bar\ClassName');
        $this->assertTrue($actual);
    }
    
    public function testMissingFile()
    {
        $actual = $this->loader->load('No_Vendor\No_Package\NoClass');
        $this->assertFalse($actual);
    }
    
    public function testDeepFile()
    {
        $actual = $this->loader->load('Foo\Bar\Baz\Dib\Zim\Gir\ClassName');
        $this->assertTrue($actual);
    }
}
