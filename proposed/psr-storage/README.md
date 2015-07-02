Storage Interface
================

This proposal is to establish a standard in storage libraries. Documentation is 
heavily based on the Logger Interface accepted PSR.

The goal is to establish a standard in read, write, update and delete operations 
on storage libraries and/or interfaces. Applications, frameworks and libraries 
will be able to implement the storage interface and perform basic storage operations
in a uniform way, without having to worry about the backend handling it.

## Interface
The interface is based on the CRUD principle. So naturally, the four methods featured 
in this interface are create, read, update and delete.

- All four methods accept a string or an object implementing the `__toString()` method 
  as a key. If an object is passed the implementor must cast it to a string.

#### Psr\Storage\StorageInterface
```php
<?php namespace Psr\Storage;

/**
 * Describes a storage interface
 *
 * The $key used in this interface must be a string or implement the __toString() method.
 *
 * Methods in this interface (except for read()) should return null, or raise a StorageException in case of failure
 */
interface StorageInterface
{
    /**
     * Create a new entry in the storage by key and value
     *
     * @param   string  $key
     * @param   mixed   $value
     * @return  null
     * @throws  StorageException
     */
    public function create($key, $value);

    /**
     * Reads the value of a stored record by key
     *
     * @param   string  $key
     * @return  mixed
     * @throws  StorageException
     */
    public function read($key);

    /**
     * Updates an existing record by key and value
     *
     * @param   string  $key
     * @param   mixed   $value
     * @return  null
     * @throws  StorageException
     */
    public function update($key, $value);

    /**
     * Deletes an existing record by key
     *
     * @param   string  $key
     * @return  null
     * @throws  StorageException
     */
    public function delete($key);

}
```
