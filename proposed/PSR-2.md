The following describes the mandatory requirements that must be adhered
to for autoloader interoperability.

Mandatory default behavior
---------

* A fully-qualified namespace and class must have the following
  structure `\<Vendor Name>\(<Namespace>\)*<Class Name>`
* Each namespace must have a top-level namespace ("Vendor Name").
* Each namespace can have as many sub-namespaces as it wishes.
* Each namespace separator is converted to a `DIRECTORY_SEPARATOR` when
  loading from the file system.
* The fully-qualified namespace and class is suffixed with ".php" when
  loading from the file system.
* Alphabetic characters in vendor names, namespaces, and class names may
  be of any combination of lower case and upper case.
* Namespace and class name is case sensitive as inherited from most
  file systems.
* If file is not present a fatal error is emitted.


The following describes opt-in alternative behavior. Support for alternative
behavior is optional. Suggested way of enabling this is by means of [class]
constants used when registering autoloader, see class example bellow.

Opt-In alternative behavior
---------
* PSR_0_PEAR_COMPAT: Each "\_" character in the CLASS NAME is
  converted to a `DIRECTORY_SEPARATOR`. The "\_" character has no
  special meaning in the namespace.
* PSR_2_FILECHECK: Adds checks to see if file exists, if not returns false.

Examples
--------

* `\Doctrine\Common\IsolatedClassLoader` => `/path/to/project/lib/vendor/Doctrine/Common/IsolatedClassLoader.php`
* `\Symfony\Core\Request` => `/path/to/project/lib/vendor/Symfony/Core/Request.php`
* `\Zend\Acl` => `/path/to/project/lib/vendor/Zend/Acl.php`
* `\Zend\Mail\Message` => `/path/to/project/lib/vendor/Zend/Mail/Message.php`

Underscores in Namespaces and Class Names
-----------------------------------------
Default PSR-2 behavior:
* `\namespace\package\Class_Name` => `/path/to/project/lib/vendor/namespace/package/Class_Name.php`
* `\namespace\package_name\Class_Name` => `/path/to/project/lib/vendor/namespace/package_name/Class_Name.php`


Using PSR_0_PEAR_COMPAT mode:
* `\namespace\package\Class_Name` => `/path/to/project/lib/vendor/namespace/package/Class/Name.php`
* `\namespace\package_name\Class_Name` => `/path/to/project/lib/vendor/namespace/package_name/Class/Name.php`

The standards we set here should be the lowest common denominator for
painless autoloader interoperability. You can test that you are
following these standards by utilizing this sample SplClassLoader
implementation which is able to load PHP 5.3 classes.

Example Minimal Implementation
----------------------

Below is an example function to simply demonstrate how the above
proposed standards are autoloaded.

    <?php
    
    function autoload($className)
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
    
        require $fileName;
    }


Example Class Implementation
----------------------

    <?php
    
    class Loader
    {
        /**
         * Mode for enabling PEAR autoloader compatibility (and PSR-0 compat)
         *
         * @var int
         */
        const PSR_0_PEAR_COMPAT = 1;
    
        /**
         * Mode to check if file exists before loading class name that matches prefix
         *
         * @var int
         */
        const PSR_2_FILECHECK = 2;
    
        /**
         * @var array Contains namespace/class prefix as key and sub path as value
         */
        protected $paths;
    
        /**
         * @var int
         */
        protected $mode;
    
        /**
         * Construct a loader instance
         *
         * @param array $paths Containing class/namespace prefix as key and sub path as value
         * @param int $mode One or more of of the PSR_ constants, these are opt-in
         */
        public function __construct( array $paths, $mode = 0 )
        {
            $this->paths = $paths;
            $this->mode = $mode;
        }
    
        /**
         * Load classes/interfaces following PSR-0 naming
         *
         * @param string $className
         * @param bool $returnFileName For testing, returns file name instead of loading it
         * @return null|boolean|string Null if no match is found, bool if match and found/not found,
         *                             string if $returnFileName is true.
         */
        public function load( $className, $returnFileName = false )
        {
            if ( $className[0] === '\\' )
                $className = substr( $className, 1 );
    
            foreach ( $this->paths as $prefix => $subPath )
            {
                if ( strpos( $className, $prefix ) !== 0 )
                    continue;
    
                if ( $this->mode & self::PSR_0_PEAR_COMPAT ) // PSR-0 / PEAR compat
                {
                    $lastNsPos = strripos( $className, '\\' );
                    $prefixLen = strlen( $prefix ) + 1;
                    $fileName = $subPath . DIRECTORY_SEPARATOR;
    
                    if ( $lastNsPos > $prefixLen )
                    {
                        // Replacing '\' to '/' in namespace part
                        $fileName .= substr(
                            strtr( substr( $className, 0, $lastNsPos ), '\\', DIRECTORY_SEPARATOR ),
                            $prefixLen
                        ) . DIRECTORY_SEPARATOR;
                    }
    
                    // Replacing '_' to '/' in className part and append '.php'
                    $fileName .= str_replace( '_', DIRECTORY_SEPARATOR, substr( $className, $lastNsPos + 1 ) ) . '.php';
                }
                else // PSR-2 Default
                {
                     // Replace prefix with sub path if different
                    if ( $prefix === $subPath )
                        $fileName = strtr( $className, '\\', DIRECTORY_SEPARATOR ) . '.php';
                    else
                        $fileName = $subPath . DIRECTORY_SEPARATOR .
                                    substr( strtr( $className, '\\', DIRECTORY_SEPARATOR ), strlen( $prefix ) +1 ) . '.php';
                }
    
                if ( ( $this->mode & self::PSR_2_FILECHECK ) && !is_file( $fileName ) )
                    return false;
    
                if ( $returnFileName )
                    return $fileName;
    
                require $fileName;
                return true;
            }
        }
    }
