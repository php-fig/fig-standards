<?php
/**
 *
 *
 * @author Bastian Feder <lapistano@php.net>
 * @copyright 2011 by Bastian Feder
 *
 */

namespace figStandards\accepted\tests\Psr0;

class Psr0_Scanner
{
    protected $includes = '';
    protected $excludes = '';
    protected $registry = array();
    protected $errors = array();

    public function __construct($includes = '', $excludes = '')
    {
        $this->includes = $includes;
        $this->excludes = $excludes;
    }

    /**
     *
     *
     * @param unknown_type $dir
     */
    public function scan($dir)
    {
        $scanner = $this->initScanner();
        $files = $scanner($dir);
        $classes = array();

        foreach ($files as $file) {
            $this->parseFile($file);
        }

        // $this->registry now has all information about used NS and classnames
        foreach ($this->registry as $namespace => $classInfo) {
            foreach ($classInfo as $class) {
                $classname = $namespace . '\\' . $class['classname'];
                $classes[$classname] = array(
                    'filename' => $class['filename'],
                    'classname' => $classname
                );
            }
        }

        return $classes;

    }

    /**
     * Provides the list of errors accord during the parsing.
     *
     * @return array
     */
    public function getErrors($type = '')
    {
        if (!empty($type) && isset($this->errors[$type])) {
            return $this->errors[$type];
        }
        return $this->errors;
    }

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
    public static function translateClassToFilename($className)
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
     * Greps namespaces and classnames from the given file
     *
     * @param SplFileInfo $file
     */
    protected function parseFile($file)
    {
        if (!$file->isReadable()) {
            $this->errors['NotReadable'][] = $file->getPathname();
            return;
        }
        $content = file($file->getPathname());

        foreach ($content as $line) {
            // find namespace declarations
            preg_match('(^\s*?namespace\s*([^;{]*))', $line, $matches);
            if (isset($matches[1])) {
                $namespace = $matches[1];
                if (!isset($this->registry[$namespace])) {
                    $this->registry[$namespace] = array();
                }
            }

            // find class declarations
            preg_match('(^\s*?(?:class|interface)\s+([^\s]+)(?:extends|implements)?\s+[^{]*)m', $line, $matches);
            if (isset($matches[1])) {
                $this->registry[$namespace][] = array(
                     'classname' => $matches[1],
                     'filename'  => $file->getPathname()
                 );
            }
        }
    }

    /**
     * Initializes the directory scanner.
     *
     * @return DirectoryScanner
     */
    protected function initScanner()
    {
        if(! class_exists('\TheSeer\DirectoryScanner\DirectoryScanner', true)) {
            $dirScanner = __DIR__ . '/vendor/TheSeer/DirectoryScanner/autoload.php';
            if (!file_exists($dirScanner)) {
                throw new \BadFunctionCallException(
                    'Cannot find directory scanner mandatory for PSR-0 cpmatibility tests. '.
                    'Please run install.sh in the test root directory.'
                );
            }
            require $dirScanner;
        }

        $scanner = new \TheSeer\DirectoryScanner\DirectoryScanner;
        $this->registerExclusions($scanner);
        $this->registerInclusions($scanner);

        return $scanner;
    }

    /**
     * Registers preset files/directories to be ignored when scanning the directory structure.
     *
     * @param DirectoryScanner $scanner
     */
    protected function registerExclusions(\TheSeer\DirectoryScanner\DirectoryScanner $scanner)
    {
        if (!empty($this->excludes)) {
            $exclusions = explode(":", $this->excludes);
            $scanner->setExcludes($exclusions);
        }
    }

    /**
     * Registers preset files/directories to be recognized when scanning the directory structure.
     *
     * @param DirectoryScanner $scanner
     */
    protected function registerInclusions(\TheSeer\DirectoryScanner\DirectoryScanner $scanner)
    {
        if (!empty($this->includes)) {
            $inclusions = explode(":", $this->includes);
            $scanner->setIncludes($inclusions);
        }
    }
}
