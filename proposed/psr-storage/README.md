Storage Interface
================

This proposal is to establish a standard in storage libraries.

The goal is to establish a standard in read, write, update and delete operations
on storage libraries and/or interfaces. Applications, frameworks and libraries
will be able to implement the storage interface and perform basic storage operations
in a uniform way, without having to worry about the backend handling it.

Use-cases are storing session variables, files, or cache data to storage systems like a filesystem, Redis, Memcached or memory.

## Interface
The interface is based on the CRUD principle. So naturally, the four methods featured
in this interface are create, read, update and delete. The `$key` parameter in this interface can either be a `string`, an object implementing the `__toString()` method, or `null`.

- An additional method `setOptions()` with an array parameter is added to this interface to allow for options to be set on the implementor.
- The `create()` method's first parameter is the value of the item to be set, to allow for an optional `$key` parameter. The `$key` parameter is optional, and it is the responsibility of the implementor to cast a given object to string, or to generate a key if none is given. This method always returns the key of the item set.
- The `read()` method requires a `$key` parameter of the item to be read from the implementor.
- The `update()` method's first parameters is the new value of the item. The second parameter is the `$key` of the item to update.
- The `delete()` method requires the `$key` parameter of the item to be deleted from the implementor.

#### Psr\Storage\StorageInterface
```php
<?php namespace Psr\Storage;

use Psr\Storage\Exception\RuntimeException;
use Psr\Storage\Exception\InvalidArgumentException;

/**
 * Describes a storage interface
 *
 * The $key used in this interface must be a string or implement the __toString() method.
 */
interface StorageInterface
{
    /**
     * Sets the options for the Storage implementor instance
     *
     * @param   array       $options
     *
     * @throws  InvalidArgumentException
     */
    public function setOptions($options = array());

    /**
     * Creates a new record in the storage by value and key (optional)
     *
     * @param   mixed       $value
     * @param   string      $key
     * @return  string
     *
     * @throws  RuntimeException
     * @throws  InvalidArgumentException
     */
    public function create($value, $key = null);

    /**
     * Reads the value of an existing record
     *
     * @param   string      $key
     * @return  mixed
     *
     * @throws  RuntimeException
     */
    public function read($key);

    /**
     * Updates an existing record by key and value
     *
     * @param   mixed       $value
     * @param   string      $key
     *
     * @throws  RuntimeException
     */
    public function update($value, $key);

    /**
     * Deletes an existing record by key
     *
     * @param   string      $key
     *
     * @throws  RuntimeException
     */
    public function delete($key);

}
```

## Exceptions

The `StorageException` is a general exception that can be caught. It is extended by more specific exceptions allow catching of more specific type of exceptions.
#### Psr\Storage\Exception\StorageException
```php
<?php namespace Psr\Storage\Exception;

class StorageException extends \Exception {}
```

The `RuntimeException` is an exception that should be thrown when the implementor was unable to perform the action called. (Example: Unable to locate Redis server at 127.0.0.1:6379)
#### Psr\Storage\Exception\RuntimeException
```php
<?php namespace Psr\Storage\Exception;

class RuntimeException extends StorageException {}
```

The `InvalidArgumentException` is thrown when an invalid argument is given in a method. (Example: `$instance->setOptions('peekaboo')`)
#### Psr\Storage\Exception\InvalidArgumentException
```php
<?php namespace Psr\Storage\Exception;

class InvalidArgumentException extends StorageException {}
```
