<?php
/**
 * Test case containing test to verify PSR-0 copatibility of a project.
 *
 * @author Bastian Feder <lapistano@php.net>
 * @copyright 2011 by Bastian Feder
 *
 */
namespace figStandards\PSR0\Compatibility;

require __DIR__ . '/Psr0_Scanner.php';

class psr0_CompatibilityTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Contains the result of the directory scan.
     * @staticvar
     * @var Psr0_Scanner
     */
    protected static $psr0;

    /**
     * @dataProvider psr0CompatibilityDataprovider
     */
    public function testPsr0Compatability($filename, $classname)
    {
        $pattern = "(^.*?(" . Psr0_ProjectRootNamespace . ".*)$)";
        $filename = preg_replace($pattern, '$1', $filename, 1);

        $this->assertEquals(
            $filename,
            Psr0_Scanner::translateClassToFilename($classname),
            'The classname does not translate correctly into a PSR-0 compatible filename.'
        );
    }

    /**
     * Checks if any permission related errors were registered.
     */
    public function testReadErrorsOccured()
    {
        $errors = self::$psr0->getErrors('NotReadable');
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
        self::$psr0 = new Psr0_Scanner(
            defined(Psr0_ScannerInclude) ? Psr0_ScannerInclude : '',
            defined(Psr0_ScannerExclude) ? Psr0_ScannerExclude : ''
        );

        return self::$psr0->scan(Psr0_ScannerStartDir);
    }
}