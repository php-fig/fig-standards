<?php
/**
 * Test case containing test to verify PSR-0 copatibility of a projext.
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
     * Translates a classname to a filename according to the rules of PSR-0.
     *
     * This method is a clone of the autoload() function accepted to be the reference
     * implementation to autoload classes following the PSR-0 FIG standard.
     *
     * @param string $className
     * @return string
     * @link https://github.com/php-fig/fig-standards
     */
    protected function translateClassToFilename($className)
    {
        $className = ltrim($className, '\\');
        $fileName  = '';
        $namespace = '';
        if ($lastNsPos = strripos($className, '\\')) {
            $namespace = substr($className, 0, $lastNsPos);
            $className = substr($className, $lastNsPos + 1);
            $fileName  = str_replace('\\', DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR;
        }
        $fileName .= str_replace('_', DIRECTORY_SEPARATOR, $className) . '.php';
        return $fileName;
    }


    /**
     * @dataProvider psr0CompatibilityDataprovider
     */
    public function testPsr0Compatability($filename, $classname)
    {
        $pattern = "(^.*?(" . Psr0_ProjectRootNamespace . ".*)$)";
        $filename = preg_replace($pattern, '$1', $filename, 1);

        $this->assertEquals(
            $filename,
            $this->translateClassToFilename($classname),
            'The classname does not translate correctly into a PSR-0 compatible filename.'
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
        $psr0 = new Psr0_Scanner(
            Psr0_ScannerInclude,
            Psr0_ScannerExclude
        );

        return $psr0->scan(Psr0_ScannerStartDir);
    }
}