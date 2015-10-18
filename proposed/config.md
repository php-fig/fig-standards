# Config interfaces

This document describes common interfaces for configuration of factories.

The goal set by the Config PSR is to standardize how factories uses a configuration to create instances, support for 
auto discovery of needed configuration, to reduce boilerplate code and to make it more readable and easier to 
understand. It can also be used to build the name of the *Container* entry.

The key words "MUST", "MUST NOT", "REQUIRED", "SHALL", "SHALL NOT", "SHOULD",
"SHOULD NOT", "RECOMMENDED", "MAY", and "OPTIONAL" in this document are to be
interpreted as described in [RFC 2119][].

The word `Container` in this document is to be interpreted as the `ContainerInterface` of the PSR Container proposal. See the [config-meta](config-meta.md) documentation for additional details.


[RFC 2119]: http://tools.ietf.org/html/rfc2119

## 1. Specification

This specification defines four interfaces where the `PSR\Config\RequiresConfig` is the main interface and 
`PSR\Config\RequiresContainerId`, `PSR\Config\RequiresMandatoryOptions`,`PSR\Config\ProvidesDefaultOptions` are optional.

### 1.1 RequiresConfig

The `PSR\Config\RequiresConfig` interface exposes four methods: `vendorName`, `packageName`, `canRetrieveOptions` and `options`.

* `vendorName` has no parameters and MUST return a string.
* `packageName` has no parameters and MUST return a string.
* `canRetrieveOptions` checks if options are available depending on implemented interfaces and checks that the retrieved options are an array or have implemented \ArrayAccess. The `RequiresContainerId` interface is optional but MUST be supported.
* `options` takes one mandatory parameter: a configuration array. It MUST be an array or an object which implements the 
`ArrayAccess` interface. A call to `options` returns the configuration depending on the implemented interfaces of the 
class or throws an exception if the parameter is invalid or if the configuration is missing or if a mandatory option is missing.

### 1.2 RequiresContainerId
The `PSR\Config\RequiresContainerId` interface exposes one method: `containerId`

* `containerId` has no parameters and MUST return a string.

### 1.3 RequiresMandatoryOptions
The `PSR\Config\RequiresMandatoryOptions` interface exposes one method: `mandatoryOptions`

* `mandatoryOptions` has no parameters and MUST return an array of strings which represents the list of mandatory 
options.

### 1.4 ProvidesDefaultOptions
The `PSR\Config\ProvidesDefaultOptions` interface exposes one method: `defaultOptions`

* `defaultOptions` has no parameters and MUST return an key value array where the key is the option name and the value is the default value for this option. This array can have a multiple depth.

### 1.5 Exceptions
Exceptions directly thrown by the `options` method MUST implement the `PSR\Config\Exception\ExceptionInterface`.

If the configuration parameter is not an array or an object which implementes the `ArrayAccess` interface the method 
SHOULD throw a `PSR\Config\Exception\InvalidArgumentException`.

If the key which is returned from  `vendorName` is not set in the configuration parameter the method SHOULD throw a 
`PSR\Config\Exception\OutOfBoundsException`.

If the key which is returned from `packageName` is not set under the key of `vendorName` in the configuration parameter 
the method SHOULD throw a `PSR\Config\Exception\OptionNotFoundException`.

If the class implements the `RequiresContainerId` interface and if the key which is returned from `containerId` is not set
under the key of `packageName` in the configuration parameter the method SHOULD throw a 
`PSR\Config\Exception\OptionNotFoundException`.

If the class implements the `RequiresMandatoryOptions` interface and if a mandatory option from `mandatoryOptions` is not set 
in the options array which was retrieved from the configuration parameter before, the method SHOULD throw a 
`PSR\Config\Exception\MandatoryOptionNotFoundException`.

## 2. Package

The interfaces and classes described as well as relevant exception are provided as part of the
[psr/config](https://packagist.org/packages/psr/config) package. (still to-be-created)

## 3. Interfaces

## 3.1 `PSR\Config\RequiresConfig`

```php
<?php
namespace PSR\Config;

use ArrayAccess;

/**
 * RequiresConfig Interface
 *
 * Use this interface if you want to retrieve options from a configuration and optional to perform a mandatory option
 * check. Default options are merged and overridden of the provided options.
 */
interface RequiresConfig
{
    /**
     * Returns the vendor name
     *
     * @return string
     */
    public function vendorName();

    /**
     * Returns the package name
     *
     * @return string
     */
    public function packageName();

    /**
     * Returns options based on [vendor][package][id] and can perform mandatory option checks if class implements
     * MandatoryOptionsInterface. If the ProvidesDefaultOptions interface is implemented, these options must be
     * overridden by the provided config. The RequiresContainerId interface is optional.
     *
     * <code>
     * return [
     *      // vendor name
     *     'doctrine' => [
     *          // package name
     *          'connection' => [
     *             // container id, is optional
     *             'orm_default' => [
     *                 // mandatory options, is optional
     *                 'driverClass' => 'Doctrine\DBAL\Driver\PDOMySql\Driver',
     *                 'params' => [
     *                     // default options, is optional
     *                     'host' => 'localhost',
     *                     'port' => '3306',
     *                 ],
     *             ],
     *         ],
     *     ],
     * ];
     * </code>
     *
     * @param array|ArrayAccess $config Configuration
     * @return array|ArrayAccess options
     *
     * @throws Exception\UnexpectedValueException If the $config parameter has the wrong type
     * @throws Exception\OutOfBoundsException If vendor name was not found
     * @throws Exception\OptionNotFoundException If no options are available
     * @throws Exception\MandatoryOptionNotFoundException If a mandatory option is missing
     */
    public function options($config);

    /**
     * Checks if options are available depending on implemented interfaces and checks that the retrieved options are an
     * array or have implemented \ArrayAccess.
     *
     * @param array|ArrayAccess $config Configuration
     * @return bool True if options are available, otherwise false
     */
    public function canRetrieveOptions($config);
}

```

## 3.2 `PSR\Config\RequiresContainerId`

```php
<?php
namespace PSR\Config;

/**
 * RequiresContainerId Interface
 *
 * Use this interface if a configuration is for a specific container id.
 */
interface RequiresContainerId extends RequiresConfig
{
    /**
     * Returns the container identifier
     *
     * @return string
     */
    public function containerId();
}

```

## 3.3 `PSR\Config\RequiresMandatoryOptions`

```php
<?php
namespace PSR\Config;

/**
 * RequiresMandatoryOptions Interface
 *
 * Use this interface if you have mandatory options
 */
interface RequiresMandatoryOptions
{
    /**
     * Returns a list of mandatory options which must be available
     *
     * @return string[] List with mandatory options
     */
    public function mandatoryOptions();
}

```

## 3.4 `PSR\Config\ProvidesDefaultOptions`

```php
<?php
namespace PSR\Config;

/**
 * ProvidesDefaultOptions Interface
 *
 * Use this interface if you have default options. These options are merged with the provided options in
 * \PSR\Config\RequiresConfig::options
 */
interface ProvidesDefaultOptions
{
    /**
     * Returns a list of default options, which are merged in \PSR\Config\RequiresConfig::options
     *
     * @return string[] List with default options and values
     */
    public function defaultOptions();
}

```

## 3.5 `PSR\Config\Exception\ExceptionInterface`

```php
<?php
namespace PSR\Config\Exception;

/**
 * Base exception interface
 *
 * All exceptions must implements this exception to catch exceptions of this library
 */
interface ExceptionInterface
{
}

```

## 3.6 `PSR\Config\Exception\InvalidArgumentException`


```php
<?php
namespace PSR\Config\Exception;

use InvalidArgumentException as PhpInvalidArgumentException;

/**
 * InvalidArgumentException exception
 *
 * Use this exception if an argument has not the expected value.
 */
class InvalidArgumentException extends PhpInvalidArgumentException implements ExceptionInterface
{
}
```

## 3.7 `PSR\Config\Exception\RuntimeException`

```php
<?php
namespace PSR\Config\Exception;

use RuntimeException as PhpRuntimeException;

/**
 * Runtime exception
 *
 * Use this exception if the code has not the capacity to handle the request.
 */
class RuntimeException extends PhpRuntimeException implements ExceptionInterface
{
}

```

## 3.8 `PSR\Config\Exception\RuntimeException`

```php
<?php
namespace PSR\Config\Exception;

use OutOfBoundsException as PhpOutOfBoundsException;

/**
 * OutOfBoundsException exception
 *
 * Use this exception if the code attempts to access an associative array, but performs a check for the key.
 */
class OutOfBoundsException extends PhpOutOfBoundsException implements ExceptionInterface
{
}

```

## 3.9 `PSR\Config\Exception\OptionNotFoundException`

```php
<?php
namespace PSR\Config\Exception;

/**
 * Option not found exception
 *
 * Use this exception if an option was not found in the config
 */
class OptionNotFoundException extends OutOfBoundsException
{
}

```

## 3.10 `PSR\Config\Exception\MandatoryOptionNotFoundException`

```php

namespace PSR\Config\Exception;

/**
 * Mandatory option not found exception
 *
 * Use this exception if a mandatory option was not found in the config
 */
class MandatoryOptionNotFoundException extends OutOfBoundsException
{
}

```
