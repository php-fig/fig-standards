<?php namespace Psr\Storage;

/**
 * Describes a storage interface
 *
 * The $key used in this interface must be a string or implement the __toString() method.
 *
 * Methods in this interface (except for read()) should return null, or raise a StorageException in case of failure.
 * A StorageException will also be raised in case the storage system is unreachable.
 */
interface StorageInterface
{
    /**
     * Create a new entry in the storage by key and value or raises a StorageException in case the given key is already used.
     *
     * @param   string  $key
     * @param   mixed   $value
     * @return  null
     * @throws  StorageException
     */
    public function create($key, $value);

    /**
     * Reads the value of a stored record by key or raises a StorageException in case the key does not exist.
     *
     * @param   string  $key
     * @return  mixed
     * @throws  StorageException
     */
    public function read($key);

    /**
     * Updates an existing record by key and value.
     *
     * @param   string  $key
     * @param   mixed   $value
     * @return  null
     * @throws  StorageException
     */
    public function update($key, $value);

    /**
     * Deletes an existing record by key or raises a StorageException in case the key does not exist.
     *
     * @param   string  $key
     * @return  null
     * @throws  StorageException
     */
    public function delete($key);

}
