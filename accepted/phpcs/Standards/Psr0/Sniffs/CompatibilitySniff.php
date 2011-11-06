<?php
/**
 * Sniff to verify the compatibility of a project to PSR-0.
 *
 * PHP version 5
 *
 * @author    Bastian Feder <lapistano@php.net>
 * @copyright 2011 Bastian Feder
 */

namespace figStandards\accepted\phpcs\Standards;

/**
 * PSR0_Sniffs_CompatibilitySniff.
 *
 * Favor PHP 5 constructor syntax, which uses "function __construct()".
 * Avoid PHP 4 constructor syntax, which uses "function ClassName()".
 *
 * @author   Bastian Feder <lapistano@php.net>
 * @copyright 2011 Bastian Feder
 */
class Psr0_Sniffs_CompatibilitySniff implements PHP_CodeSniffer_Sniff
{
    public $supportedTokenizers = array('PHP');

    /**
     * Registers the tokens that this sniff wants to listen for.
     *
     * An example return value for a sniff that wants to listen for whitespace
     * and any comments would be:
     *
     * <code>
     *    return array(
     *            T_WHITESPACE,
     *            T_DOC_COMMENT,
     *            T_COMMENT,
     *           );
     * </code>
     *
     * @return array(int)
     * @see    Tokens.php
     */
    public function register()
    {
        return array(
             T_CLASS,
             T_INTERFACE,
        );
    }

    /**
     * Called when one of the token types that this sniff is listening for
     * is found.
     *
     * The stackPtr variable indicates where in the stack the token was found.
     * A sniff can acquire information this token, along with all the other
     * tokens within the stack by first acquiring the token stack:
     *
     * <code>
     *    $tokens = $phpcsFile->getTokens();
     *    echo 'Encountered a '.$tokens[$stackPtr]['type'].' token';
     *    echo 'token information: ';
     *    print_r($tokens[$stackPtr]);
     * </code>
     *
     * If the sniff discovers an anomilty in the code, they can raise an error
     * by calling addError() on the PHP_CodeSniffer_File object, specifying an error
     * message and the position of the offending token:
     *
     * <code>
     *    $phpcsFile->addError('Encountered an error', $stackPtr);
     * </code>
     *
     * @param PHP_CodeSniffer_File $phpcsFile The PHP_CodeSniffer file where the
     *                                        token was found.
     * @param int                  $stackPtr  The position in the PHP_CodeSniffer
     *                                        file's token stack where the token
     *                                        was found.
     *
     * @return void
     */
    public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();
        $filename = $phpcsFile->getFilename();
        $declarationName = $phpcsFile->getDeclarationName($stackPtr);
        $namespace = $this->getNamespaceName($tokens, $phpcsFile->findPrevious(T_NAMESPACE, $stackPtr));
        $fulldecla = $namespace . '\\' . $declarationName;

        $fname = $this->translateClassToFilename($fulldecla);


        if (false === strpos($filename, $fname)) {
            $error = sprintf(
                'The classname (%s) does not translate correctly into a PSR-0 compatible filename.',
                $fulldecla
            );
            $phpcsFile->addError($error, $stackPtr);
        }
    }

    /**
     * Crawls the namespace from the current file.
     *
     * @param array $tokens
     * @param integer $stackPtr
     * @return string
     */
    protected function getNamespaceName($tokens, $stackPtr)
    {
        $namespaceName = '';

        while(isset($tokens[$stackPtr])) {
            if (in_array($tokens[$stackPtr]['code'], array(T_STRING, T_NS_SEPARATOR))) {
                $namespaceName .= $tokens[$stackPtr]['content'];
            }
            if ($tokens[$stackPtr]['code'] == T_SEMICOLON) {
                break;
            }
            ++$stackPtr;
        }
        return $namespaceName;
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
}
