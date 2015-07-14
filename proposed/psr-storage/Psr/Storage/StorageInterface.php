<?php namespace Psr\Storage;

use Psr\Storage\Exception\RuntimeException;
use Psr\Storage\Exception\InvalidArgumentException;

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
     * @return  mixed
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
     * @param   mixed       $key
     *
     * @throws  RuntimeException
     */
    public function update($value, $key);

    /**
     * Deletes an existing record by key
     *
     * @param   mixed       $key
     *
     * @throws  RuntimeException
     */
    public function delete($key);

}
