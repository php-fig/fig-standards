# Config interfaces

This document describes common interfaces for configuration of factories.

The goal set by the Config PSR is to standardize how factories uses a configuration to create instances, support for 
auto discovery of needed configuration, to reduce boilerplate code and to make it more readable and easier to 
understand. It can also be used to build the name of the *Container* entry.

The key words "MUST", "MUST NOT", "REQUIRED", "SHALL", "SHALL NOT", "SHOULD",
"SHOULD NOT", "RECOMMENDED", "MAY", and "OPTIONAL" in this document are to be
interpreted as described in [RFC 2119][].

The word `Container` in this document is to be interpreted as the `ContainerInterface` of the PSR Container proposal.


[RFC 2119]: http://tools.ietf.org/html/rfc2119

## 1. Specification

### 1.1 HasConfig

The `PSR\Config\HasConfig` interface exposes two methods: `vendorName` and `packageName`

* `vendorName` has no parameters and MUST return a string.
* `packageName` has no parameters and MUST return a string.

### 1.2 HasContainerId
The `PSR\Config\HasContainerId` interface exposes one method: `containerId`

* `containerId` has no parameters and MUST return a string.

### 1.3 HasMandatoryOptions
The `PSR\Config\HasMandatoryOptions` interface exposes one method: `mandatoryOptions`

* `mandatoryOptions` has no parameters and MUST return an array of strings which represents the list of mandatory 
options.

### 1.4 ObtainsOptions

The `PSR\Config\ObtainsOptions` interface exposes one method: `options`

* `options` takes one mandatory parameter: a configuration array. It MUST be an array or an object which implements the 
`ArrayAccess` interface. A call to `options` returns the configuration depending on the implemented interfaces of the 
class or throws an exception if the parameter is invalid or if the configuration is missing or if a mandatory option is missing.

### 1.5 Exceptions
Exceptions directly thrown by the `options` method MUST implement the `PSR\Config\Exception\ExceptionInterface`.

If the configuration parameter is not an array or an object which implementes the `ArrayAccess` interface the method 
SHOULD throw a `PSR\Config\Exception\InvalidArgumentException`.

If the key which is returned from `vendorName` is not set in the configuration parameter the method SHOULD throw a 
`PSR\Config\Exception\InvalidArgumentException`.

If the key which is returned from `packageName` is not set under the key of `vendorName` in the configuration parameter 
the method SHOULD throw a `PSR\Config\Exception\OptionNotFoundException`.

If the class implements the `HasContainerId` interface and if the key which is returned from `containerId` is not set
under the key of `packageName` in the configuration parameter the method SHOULD throw a 
`PSR\Config\Exception\OptionNotFoundException`.

If the class implements the `HasMandatoryOptions` interface and if a mandatory option from `mandatoryOptions` is not set 
in the options array which was retrieved from the configuration parameter before, the method SHOULD throw a 
`PSR\Config\Exception\MandatoryOptionNotFoundException`.

## 2. Package

The interfaces and classes described as well as relevant exception are provided as part of the
[psr/config](https://packagist.org/packages/psr/config) package. (still to-be-created)

## 3. Interfaces

## 3.1 `PSR\Config\HasConfig`

```php
<?php
namespace PSR\Config;

/**
 * HasConfig Interface
 *
 * Use this interface if you want to use a configuration
 */
interface HasConfig
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
}
```

## 3.2 `PSR\Config\HasContainerId`

```php
<?php
namespace PSR\Config;

/**
 * HasContainerId Interface
 *
 * Use this interface if a configuration is for a specific container id.
 */
interface HasContainerId extends HasConfig
{
    /**
     * Returns the container identifier
     *
     * @return string
     */
    public function containerId();
}

```

## 3.3 `PSR\Config\HasMandatoryOptions`

```php
<?php
namespace PSR\Config;

/**
 * HasMandatoryOptions Interface
 *
 * Use this interface if you have mandatory options
 */
interface HasMandatoryOptions
{
    /**
     * Returns a list of mandatory options which must be available
     *
     * @return string[] List with mandatory options
     */
    public function mandatoryOptions();
}

```

## 3.4 `PSR\Config\ObtainsOptions`

```php
<?php
namespace PSR\Config;

use ArrayAccess;

/**
 * ObtainOptions Interface
 *
 * Use this interface if you want to retrieve options from a configuration and optional to perform a mandatory option
 * check.
 */
interface ObtainsOptions extends HasConfig
{
    /**
     * Returns options based on [vendor][package][id] and can perform mandatory option checks if class implements
     * MandatoryOptionsInterface. The HasContainerId interface is optional.
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
     *                 'params' => [],
     *             ],
     *         ],
     *     ],
     * ];
     * </code>
     *
     * @param array|ArrayAccess $config Configuration
     * @return mixed options
     *
     * @throws Exception\InvalidArgumentException If the $config parameter has the wrong type
     * @throws Exception\RuntimeException If vendor name was not found
     * @throws Exception\OptionNotFoundException If no options are available
     * @throws Exception\MandatoryOptionNotFoundException If a mandatory option is missing
     */
    public function options($config);
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

## 3.8 `PSR\Config\Exception\OptionNotFoundException`

```php
<?php
namespace PSR\Config\Exception;

/**
 * Option not found exception
 *
 * Use this exception if an option was not found in the config
 */
class OptionNotFoundException extends RuntimeException
{
}

```

## 3.8 `PSR\Config\Exception\MandatoryOptionNotFoundException`

```php

namespace PSR\Config\Exception;

/**
 * Mandatory option not found exception
 *
 * Use this exception if a mandatory option was not found in the config
 */
class MandatoryOptionNotFoundException extends RuntimeException
{
}

```
