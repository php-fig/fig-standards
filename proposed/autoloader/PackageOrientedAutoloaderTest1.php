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
     * Registers a path for a namespace prefix.
     * 
     * @param string $ns The namespace prefix.
     * 
     * @param string $path The directory containing classes in that namespace.
     * 
     * @return void
     * 
     */
    public function addNamespace($ns, $path)
    {
        $path = rtrim($path, DIRECTORY_SEPARATOR);
        $this->paths[$ns][] = $path;
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
        
                // create a complete file name from the path and partial name
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
            '/vendor/foo.bar.baz.dib/src/ClassName.php',
            '/vendor/foo.bar.baz.dib.zim.gir/src/ClassName.php',
        ));
        
        $this->loader->addNamespace(
            'Foo\Bar',
            '/vendor/foo.bar/src'
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
