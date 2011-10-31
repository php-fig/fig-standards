<?php
/**
 * Test case containing test to verify PSR-0 copatibility of a project.
 *
 * @author Bastian Feder <lapistano@php.net>
 * @copyright 2011 by Bastian Feder
 *
 */
namespace figStandards\accepted\tests\Psr0;

require __DIR__ . '/Scanner.php';

class CompatibilityTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Contains the result of the directory scan.
     * @staticvar
     * @var Psr0_Scanner
     */
    protected static $psr0;

    /**
     * Normalize given filename to a relative path.
     *
     * @param string $filename
     * @return string
     */
    protected function normalizeFilename($filename)
    {
        $pattern = array();
        $namespaces = explode(':', Psr0_ProjectRootNamespaces);

        if (empty($namespaces)) {
            return $filename;
        }

        foreach ($namespaces as $namespace) {
            $pattern[] = "(^.*?(" . $namespace . ".*)$)";
        }

        $filename = preg_replace($pattern, '$1', $filename, 1);
        return $filename;
    }

    /**
     * @dataProvider psr0CompatibilityDataprovider
     */
    public function testPsr0Compatability($filename, $classname)
    {
        $this->assertEquals(
            $this->normalizeFilename($filename),
            Scanner::translateClassToFilename($classname),
            'The classname does not translate correctly into a PSR-0 compatible filename.'
        );
    }

    /**
     * Checks if any permission related errors were registered.
     */
    public function testReadErrorsOccured()
    {
        $errors = static::$psr0->getErrors('NotReadable');
        $this->assertEmpty(
            $errors,
            "The following files could not been readed probably due to missing access permissions:\n\n".
            implode(", \n", $errors).
            "\n"
        );
    }


/*************************************************************************/
/* Dataprovider                                                          */
/*************************************************************************/

    /**
     * This data provider gathers all filenames, classnames, and namespaces within the configured directory.
     *
     * @return array
     */
    public static function psr0CompatibilityDataprovider()
    {
        // preparations
        static::$psr0 = new Scanner(
            defined('Psr0_ScannerInclude') ? Psr0_ScannerInclude : '',
            defined('Psr0_ScannerExclude') ? Psr0_ScannerExclude : ''
        );

        return static::$psr0->scan(Psr0_ScannerStartDir);
    }
}