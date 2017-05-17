Module Identification
=====================

This standard declares an extension to the phar package file format. This extensions helps to
identify a package and helps to handle ways for working with them/ including them.

The key words "MUST", "MUST NOT", "REQUIRED", "SHALL", "SHALL NOT", "SHOULD",
"SHOULD NOT", "RECOMMENDED", "MAY", and "OPTIONAL" in this document are to be
interpreted as described in [RFC 2119][].

[RFC 2119]: http://www.ietf.org/rfc/rfc2119.txt
[RFC 3339]: http://www.ietf.org/rfc/rfc3339.txt
[PSR-0]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-0.md
[module-identification]: https://github.com/mepeisen/fig-standards/blob/master/proposed/module-identification.md
[composer.json]: http://getcomposer.org/doc/04-schema.md#json-schema


1. Overview
-----------

- All file names MUST be case sensitive.

- Extensions on PHAR packages MUST be fully compatible to original PHAR file format. 

- Extensions are located as files within the folder /PHP-INF.

- User defined extensions are extensions that are not covered by a final PSR.

- Known extensions are extensions that are covered by a final PSR.


2. Module manifest
------------------

### 2.1. The manifest file location

The manifest file MUST always be present and located in /PHP-INF/MANIFEST.INI
or as a file [composer.json][] in root folder.

PHP-INF is chosen because it is incompatible to [PSR-0] and cannot conflict with
classes inside a package.

If the manifest file is not found within a phar this phar is not an extended phar
although it MAY contain other files inside the PHP-INF folder.

### 2.2 Module identification and header

The manifest file MUST contain a module section that identifies the module.
The details about the modules identification are covered by [module-identification][].

Example:

    [module]
    vendorid = net.myvendor
    moduleid = MyUserLib
    version = 1.4.0
    classifier = phar
    manifest.version = 1.0
    
The vendorid, moduleid and version are required. The classifier is optional and defaults to
"phar".

The manifest version is optional but MUST NOT be any other value than the default "1.0".
It MUST be incremented if a newer PSR changes the manifest in a way that it becomes incompatible
to this PSR. If an implementor sees a manifest.version it does not understand he MUST fail to
load/use the phar file.

The following values are optional to describe the module. They MAY be displayed by a framework
or tool but do not have any technical reason. They can be silently ignored.

    [module]
    vendorid = net.myvendor
    moduleid = MyUserLib
    version = 1.4.0
    classifier = phar
    manifest.version = 1.0
    
    created.by = any-build-tool Version 4.6.5 on Linux x64
    # created.date contains [RFC 3339][] timestamps.
    created.date = 1985-04-12T23:20:50.52Z
    created.author = MyVendor
    
    license = LGPL V3, see http://www.gnu.org/licenses/lgpl.html
    title = Some short human readable module name
    description = (english) Description on this module.
    description.de = (german) Irgendeine sinnvolle Beschreibung.
    description.fr = (french) Toute description significative.
    
If there is any additional key in this section it SHOULD be silently ignored.

### 2.3 Composer

If there is a [composer.json][] it is preferred for reading the module identification or
dependencies. A PHP-INF/MANIFEST.INI MAY provide some additional information on top of this
and MUST use the following content in module section:

     [module]
     type = composer

### 2.4 Module extensions

Extensions MUST be specified by adding a extension section in the modules ini file. This extension
section contains names and types of the extensions that are present in the manifest and phar file
at all. The autoloading in this example is only used to show the way extensions behave. See the
phar autoloading PSR or [composer.json][] for details on declaring autoloading in phar files.

    [extensions]
    list = autoload, x_encryption, packager, seal, x_zend, x_flow3
    required = autoload, packager, x_encryption
    
    autoload.extension_name = psr:autoload-extension
    autoload.extension_spec_version = 1.0
    autoload.extension_impl = com.mycompany:autoload:[0.9,1.4):phar
    
    packager.extension_name = psr:packager-extension
    packager.extension_spec_version = 1.4
    packager.extension_impl = psr:packager-extension:[1.4,):phar
    
    # etc. each extension requires an extension_name and an extension_spec_version
    
    [autoload]
    include.path = /classes
    namespace = myvendor\\mylib
    
At first all extensions MUST be listed in a comma separated string.

Each extension that MUST be understood by the implementor MUST be listed in a required entry.

If there is no required entry the implementor MUST assume all extensions are optional. In this example
the extensions "seal", "x_zend" and "x_flow3" are optional and MAY be silently ignored if the implementor
does not know how to handle them.

If there is an extension listed that does not resolve to an "extension_name" or "extension_spec_version"
it MAY be ignored for optional extensions. But the implementor SHOULD print a warning. If a required
extension does not resolve to an "extension_name" or "extension_spec_version" the implementor MUST fail
to load the phar.

If there is any additional key in this section it SHOULD be silently ignored.

If an implementor does not know how to handle an extension that is listed on the required key it MUST
fail to load this phar file.

Extensions that are not covered by a PSR must start with "x_" in their name. Implementors MUST NOT
assume that a build tool always uses the desired name for the list. Instead an implementor MUST
have a look at the "extension_name" and the "extension_spec_version" to locate an extension and the proper
values.

Example:
- To apply an autoloading the extension "autoload" is introduced by another PSR based on [PSR-0][].
- The implementor first looks at the extension_name leading to "psr:autoload-extension" and this
  resolves to "autoload" extension.
- The implementor know reads "autoload.extension_spec_version" and "autoload.extension_impl" and tries to
  initialize an autoloading.
- The autoloading extension reads the ini section [autoload] and registers the proper autoloader.

The ordering within the key "list" is important and MUST be followed by the implementor. This means the
implementor MUST NOT load extension "packager" before extension "autoload".

### 2.3 Loading the extension

The extensions key "xxxx.extension_impl" is optional. It is a suggestion which module to take for loading
and understanding the given extension. The implementor SHOULD prefer the extension_impl if it is present.

The keys "xxxx.extension_name" and "xxxx.extension_spec_version" are required. They are a symbolic link to
a module identified by [module-identification][]. If there is no "xxxx.extension_impl" they SHOULD be used
by an implementor to understand the extension.

An implementor MAY choose to resolve extension "xxxx.extension_name" and "xxxx.extension_spec_version" to
already known modules and prefer them for loading. For example a framework has a custom module loading
code that is aware of autoloading it MAY use its own implementation for autoloading as long as it supports
the spec version.

Loading the Extension means to load the extensions implementation module. The extension registers a class
that is invoked as soon as a new module is loaded that lists this extension. 

The extension itself is a phar archive with the following restrictions:
- It MUST NOT contain any extensions by themselves except the autoload extension in the first version (see
  PSR-TODO (autoload extension PSR).
- It MUST contain the key "extends" in the module section of the manifest and a proper "extension" section.

Example:
    
    [module]
    ...
    extends = true
    
    [extension]
    spec.name = psr:autoload-extension
    spec.version = [1,0,1.1]
    class = net\\myvendor\\ExtensionLoader
    init = boot/init.php

In this example the extension registers itself to handle autoloading for spec-version 1.0 up to 1.1.
The class "net\myvendor\ExtensionLoader" is used to handle the autoloading for each new module.
The optional key "init" is used to invoke a script for starting the extension. The init script is located
from root path of the phar file and can be used to load the classes of the extension.


3. Module registry API
----------------------

All API classes are part of the following module:
vendorId = PSR
moduleId = PharApi
version = 1.0 
classifier = phar

### 3.0 Module registry (singleton)

This class is not part of the API. Instead an implementor must provide it to allow access to the
module registry.

    <?php
    
    namespace PSR\PharApi;
    
    class ModuleRegistry {
    
        /**
         * hidden constructor
         */
        private function __construct() {
        }
        
        /**
         * Returns the registry instance.
         * @return \PSR\PharApi\ModuleRegistryInterface
         */
        public static function instance() {
            // ... any implementation.
        }
    
    }

### 3.1 Module registry interface

    <?php
    
    namespace PSR\PharApi;
    
    /**
     * Interface to access the modules loaded at runtime.
     */
    interface ModuleRegistryInterface {
        
        /**
         * Lists all available modules.
         * @return array(\PSR\PharApi\ModuleInterface)
         */
        public function listModules();
        
        /**
         * Lists all active modules.
         * @return array(\PSR\PharApi\ModuleInterface)
         */
        public function listActiveModules();
        
        /**
         * Lists all inactive modules.
         * @return array(\PSR\PharApi\ModuleInterface)
         * @throws \Exception may be thrown on errors or access denied situations.
         */
        public function listInactiveModules();
        
        /**
         * Tries to activate given module.
         * @param \PSR\PharApi\ModuleInterface $module the module being activated.
         * @throws \Exception may be thrown on errors or access denied situations.
         */ 
        public function activateModule(\PSR\PharApi\ModuleInterface $module);
        
        /**
         * Tries to deactivate given module.
         * @param \PSR\PharApi\ModuleInterface $module the module being deactivated.
         * @throws \Exception may be thrown on errors or access denied situations.
         */ 
        public function deactivateModule(\PSR\PharApi\ModuleInterface $module);
        
        /**
         * Returns the module with given vendor id and module id.
         * @param string $vendorId the vendor id.
         * @param string $moduleId the module id.
         * @return \PSR\PharApi\ModuleInterface The module id or null if the module is not available.
         * If the callee is not allowed to access given module this method will return null.
         */
        public function getModule($vendorId, $moduleId);
        
        /**
         * Parses a version string.
         * @param string $versionString the version in SemVer String format
         * @return \PSR\PharApi\VersionInterface version or null if it cannot be parsed/ is illegal.
         */
        public function parseVersion($versionString);
        
    }
    
### 3.2 module interface

    <?php
    
    namespace PSR\PharApi;
    
    /**
     * Interface to access the modules.
     */
    interface ModuleInterface {
        
        /**
         * Returns true if this module is active.
         * @return bool true if this is an active module.
         */
        public function isActive();
        
        /**
         * Returns the vendor id of this module.
         * @return string
         */
        public function getVendorId();
        
        /**
         * Returns the module id of this module.
         * @return string
         */
        public function getModuleId();
        
        /**
         * Returns the version of this module.
         * @return \PSR\PharApi\VersionInterface
         */
        public function getVersion();
        
        /**
         * Returns the version of this module as string.
         * @return string
         */
        public function getVersionString();
        
        /**
         * Returns the version of the manifest.
         * @return \PSR\PharApi\VersionInterface
         */
        public function getManifestVersion();
        
        /**
         * Returns the manifest.
         * @return array The manifest file as returned by parse_ini_file($content, true); null if
         * there is no manifest file.
         */
        public function getManifest();
        
        /**
         * Returns the composer.json contents.
         * @return Object the object as returned from json_decode($content); null if there is
         * no composer.json file.
         */
        public function getComposer();
        
        /**
         * Returns the path to the phar file.
         * @return string The path including the wrapper. If the phar file is extracted
         * it returns the local path, otherwise a path including the "phar://" prefix.
         * The path includes a slash at the end of the string.
         */
        public function getPath();
        
    }

### 3.3 extension interface

    <?php
    
    namespace PSR\PharApi;

    /**
     * This interface is intended to be implemented by extensions.
     * See "PHP-INF/MANIFEST.MF", section "[extension]", key "class".
     * An object of this class is created once (similar to a  singleton).
     * It must provide a public constructor without parameters. 
     */
    interface ExtensionInterface {
        
        /**
         * Loads the given module and performs the code to activate this extension on given module.
         * @param \PSR\PharApi\ModuleInterface $module The module to be loaded.
         * @throws \Exception thrown of something goes wrong. The loader will assume that
         * the extension could not loaded. For optional extensions the extension itself will be ignored.
         * For required extensions the whole module is failing and cannot be loaded.
         */
        public function load(\PSR\PharApi\ModuleInterface $module);
        
        /**
         * This method is called once the modules are loaded and every extension is loaded.
         * It is meant to be the point where the application is ready to be started.
         */
        public function starting();
        
        /**
         * This method is called once the application finished the work. This method
         * may not be invoked (for example if the code exits with die).
         */
        public function stopping();
        
    }

### 3.4 version interface
    
    <?php
    
    namespace PSR\PharApi;
    
    /**
     * This interface represents a parsed version.
     */
    interface VersionInterface {
    
        /**
         * Returns the major version part.
         * @return integer non-negative version number
         */
        public function getMajor();
        
        /**
         * Returns the minor version part.
         * @return integer non-negative version number
         */
        public function getMinor();
        
        /**
         * Returns the path version part.
         * @return integer non-negative version number
         */
        public function getPatch();
        
        /**
         * Returns the extra information (including the minus or plus sign); returns everything
         * after the patch number.
         * @return string
         */
        public function getExtra();
        
        /**
         * Returns true if this is a pre release version.
         * @return true if getMajor is zero.
         */
        public function isPreRelease();
        
        /**
         * Returns true if this is an alpha version.
         * @return true for alpha versions.
         */
        public function isAlpha();
        
        /**
         * Returns true if this is a beta version.
         * @return true for beta versions.
         */
        public function isBeta();
        
        /**
         * Returns true if this is a release candidate version.
         * @return true for release candidate versions.
         */
        public function isRC();
        
        /**
         * Returns true if this is a stable version.
         * @return true for stable versions (non-pre-release, non-alpha, non-beta, non-rc, non-dev).
         */
        public function isStable();
        
        /**
         * Returns true if this is a development version.
         * @return true for development versions.
         */
        public function isDev();
        
        /**
         * Skip comparing the extra part of the version number. Will only compare the
         * major.minor.patch.
         * @see #compareTo
         */
        const COMPARE_WITHOUT_EXTRA = 1;
        
        /**
         * Treats dev versions as regular versions. If this flag is not used dev versions are
         * directly passed to version_compare and will be considered lower than their release
         * counterparts.
         * @see #compareTo
         */
        const COMPARE_TREAT_DEV_AS_REGULAR = 2;
        
        /**
         * Compares two version numbers; synonym for version_compare($this->toString(), $other->toString())
         * @param \PSR\PharApi\VersionInterface $other The other version.
         * @return 0 if both are identical; -1 if this version is lower than the other; 1 if this version is higher
         * than the other.
         */
        public function compareTo(\PSR\PharApi\VersionInterface $other, $mode = 0);
        
        /**
         * Returns the string format for this version.
         * @return string variant for this version.
         */
        public function toString();
    
    }

### 3.5 repository interface

    <?php
    
    namespace PSR\PharApi;
    
    /**
     * This interface provides access to search and find modules.
     * It is an optional interface that may be implemented by a module
     * registry.
     */
    interface RepositoryInterface {
        
        /**
         * Lists all available vendors.
         * @param int $start the start index
         * @param int $limit maximum amount of results to be returned.
         * @return array(string)
         * @throws \Exception may be thrown on errors or access denied situations.
         */
        public function listVendors($start = 0, $limit = -1);
        
        /**
         * Lists all available modules for a vendor.
         * @param string $vendorId
         * @param int $start the start index
         * @param int $limit maximum amount of results to be returned.
         * @return array(\PSR\PharApi\ModuleInterface)
         * @throws \Exception may be thrown on errors or access denied situations.
         */
        public function listModules($vendorId, $start = 0, $limit = -1);
        
        /**
         * Lists all known versions of a module sorted by version number; the newest version is returned on top of the array.
         * @param \PSR\PharApi\ModuleInterface $module
         * @param int $start the start index
         * @param int $limit maximum amount of results to be returned.
         * @return array(\PSR\PharApi\VersionInterface)
         * @throws \Exception may be thrown on errors or access denied situations.
         */
        public function listModuleVersions(\PSR\PharApi\ModuleInterface $module, $start = 0, $limit = -1);
        
        /**
         * Lists all known versions for multiple modules.
         * @param array(\PSR\PharApi\ModuleInterface) $modules
         * @param int $limit maximum amount of results to be returned. (per module)
         * @return array(string=>array(string=>array(\PSR\PharApi\VersionInterface)) first key is the vendor id; second key is the module id
         * and the array is ordered as described in method listModuleVersion).
         * @throws \Exception may be thrown on errors or access denied situations.
         */
        public function listModulesVersions(array $modules, $limit = -1);
        
        /**
         * Trys to install given module. If the module is already installed an upgrade may be
         * performed.
         * @param \PSR\PharApi\ModuleInterface $module the module to be installed.
         * @param \PSR\PharApi\VersionInterface $version the version to be installed.
         * @throws \Exception may be thrown on errors or access denied situations.
         */
        public function install(\PSR\PharApi\ModuleInterface $module, \PSR\PharApi\VersionInterface $version);
        
        /**
         * Trys to uninstall given module.
         * @param \PSR\PharApi\ModuleInterface $module the module to be uninstalled.
         * @throws \Exception may be thrown on errors or access denied situations.
         */
        public function install(\PSR\PharApi\ModuleInterface $module);
        
        /**
         * Finds a module by packaging type.
         * @param string $packageType the packaging type as defined by composer.
         * @param int $start the start index
         * @param int $limit maximum amount of results to be returned.
         * @return array(\PSR\PharApi\ModuleInterface) the modules that were found.
         * @throws \Exception may be thrown on errors or access denied situations.
         */
        public function findByPackageType($packageType, $start = 0, $limit = -1);
        
        /**
         * Finds a module by packaging type and tag. Notice that not all repositories
         * may support tagging modules with a certain tag name.
         * @param string $packageType the packaging type as defined by composer; null to
         * only search by tag names and not by packaging type.
         * @param string $tag the tag name (f.e. "database")
         * @param int $start the start index
         * @param int $limit maximum amount of results to be returned.
         * @return array(\PSR\PharApi\ModuleInterface) the modules that were found.
         * @throws \Exception may be thrown on errors or access denied situations.
         */
        public function findByTag($packageType, $tag, $start = 0, $limit = -1);
        
    }


    
4. Caching
----------

Building and parsing extended phar files MAY be time consuming.

Implementors MAY assume that release versions (non SNAPSHOT as specified in [module-identification][])
can always be cached. Even if the file is newer an implementor MAY use the cache instead analyzing the phars
and reloading the extensions.

If the filename changed (or a file has gone) the implementor MUST throw away the cache because another
module or extension may fail.

For SNAPSHOT versions the implementor should always test for the file timestamp. As soon as the file is newer the
cache MUST be invalidated.

If the path changed (f.e. the application was installed to a newer path) the cache MUST be invalidated.

Implementors MAY choose to cache/serialize the ExtensionInterface objects. Thus every module providing an extension
MUST be aware of serialization.

If the phar file is already unpacked (for example during development time in IDE workspaces) the caching and
management of the module SHOULD be the same.


5. Loading order
----------------

All Modules are analyzed and loaded in the first step.

An implementor MUST ensure that all modules and their active/inactive flag are known before any extension is loaded.

The second step MUST ensure that the extensions will be loaded. Thus the first thing MUST be to load and initialize the
extension modules and their autoloading scheme. There is no guaranteed order which extension module is loaded first but
an implementor SHALL try to load the extensions providing auto loading functionality first.

The third step is to invoke the load method of ExtensionInterface for each module. There is no guaranteed order which
module is first but an implementor MUST guarantee that the order of the extensions at the list-key in the manifest is
respected.

Extensions MUST NOT assume that the modules from registry are really loaded even if they are marked active. And Extensions
MUST NOT assume that they are already loaded.
