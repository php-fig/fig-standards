<?php
/**
 * Example implementation.
 * 
 * Note that this is only an example, and is not a specification in itself.
 * 
 * @param string $logical_path The logical path to transform.
 * @param string $logical_prefix The logical prefix associated with $dir_prefix.
 * @param string $logical_sep The logical separator in the logical path.
 * @param string $dir_prefix The directory prefix for the transformation.
 * @return string The logical path transformed into a file system path.
 */
function transform(
    $logical_path,
    $logical_prefix,
    $logical_sep,
    $dir_prefix
) {
    // normalize logical path: leading logical separator
    $logical_path = $logical_sep . ltrim($logical_path, $logical_sep);
    
    // normalize logical prefix: leading and trailing logical separators.
    // do this in two steps so that we don't destroy a single separator.
    $logical_prefix = $logical_sep . ltrim($logical_prefix, $logical_sep);
    $logical_prefix = rtrim($logical_prefix, $logical_sep) . $logical_sep;
    
    // normalize directory prefix: trailing directory separator
    $dir_prefix = rtrim($dir_prefix, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
    
    // make sure prefixes match exactly
    $len = strlen($logical_prefix);
    if (substr($logical_path, 0, $len) !== $logical_prefix) {
        return false;
    }
    
    // extract the logical suffix
    $logical_suffix = substr($logical_path, $len);
    
    // complete the transformation
    return $dir_prefix
         . str_replace($logical_sep, DIRECTORY_SEPARATOR, $logical_suffix);
}

class TransformTest extends PHPUnit_Framework_TestCase
{
    public function testLogicalPathSameAsPrefix()
    {
        $expect = '/path/to/foo-bar/';
        $actual = transform(
            'Foo:Bar:',
            'Foo:Bar:',
            ':',
            '/path/to/foo-bar'
        );
        $this->assertSame($expect, $actual);
        
        $expect = '/path/to/foo-bar/';
        $actual = transform(
            'Foo:Bar:',
            'Foo:Bar', // will get a colon added to it
            ':',
            '/path/to/foo-bar'
        );
        $this->assertSame($expect, $actual);
    }
    
    public function testLogicalPrefixIsRoot()
    {
        $expect = '/path/to/root/Foo/Bar';
        $actual = transform(
            'Foo:Bar',
            ':',
            ':',
            '/path/to/root/'
        );
        $this->assertSame($expect, $actual);
    }
    
    public function testClassName()
    {
        $expect = "/path/to/foo-bar/src/Baz/Qux.php";
        $actual = transform(
            '\Foo\Bar\Baz\Qux',
            '\Foo\Bar',
            '\\',
            '/path/to/foo-bar/src'
        ) . '.php';
        $this->assertSame($expect, $actual);
    }
    
    public function testResourceName()
    {
        $expect = "/path/to/foo-bar/resources/Baz/Qux.yml";
        $actual = transform(
            'Foo:Bar:Baz:Qux',
            'Foo:Bar',
            ':',
            '/path/to/foo-bar/resources'
        ) . '.yml';
        $this->assertSame($expect, $actual);
    }
    
    public function testOtherName()
    {
        $expect = "/path/to/foo-bar/other/Baz/Qux";
        $actual = transform(
            '/Foo/Bar/Baz/Qux',
            '/Foo/Bar',
            '/',
            '/path/to/foo-bar/other'
        );
        $this->assertSame($expect, $actual);
    }
    
    public function testDirectoryName()
    {
        $expect = "/path/to/foo-bar/other/Baz/Qux/";
        $actual = transform(
            '/Foo/Bar/Baz/Qux',
            '/Foo/Bar',
            '/',
            '/path/to/foo-bar/other'
        ) . '/';
        $this->assertSame($expect, $actual);
    }
    
    public function testBSPrefixWithFileExtension()
    {
        $expect = "/src/ShowController.php";
        $actual = transform(
            '\\Acme\\Blog\\ShowController.php',
            '\\Acme\\Blog',
            '\\',
            '/src'
        );
        $this->assertSame($expect, $actual);
    }
    
    public function testBSDirectory()
    {
        $expect = "/src/";
        $actual = transform(
            '\\Acme\\Blog\\',
            '\\Acme\\Blog\\',
            '\\',
            '/src/'
        );
        $this->assertSame($expect, $actual);
    }
    
    public function testBSFileAsPrefix()
    {
        $actual = transform(
            '\\Acme\\Blog\\ShowController.php',
            '\\Acme\\Blog\\ShowController.php',
            '\\',
            '/src/acme-blog-show-controller.php'
        );
        // files are not allowed as prefixes
        $this->assertFalse($actual);
    }

    public function testBSRoot()
    {
        $expect = "/src/Acme/Blog/ShowController.php";
        $actual = transform(
            '\\Acme\\Blog\\ShowController.php',
            '\\',
            '\\',
            '/src/'
        );
        $this->assertSame($expect, $actual);
    }

    public function testLogicalPathBaseTooLong()
    {
        $actual = transform(
            '\\Acme\\Blog',
            '\\Acme\\Blog\\Baz',
            '\\',
            '/src/'
        );
        $this->assertFalse($actual);
   }
}
