<?php

namespace Wcs\cache;


/**
 * Class NoneCache
 * Stub class for using without cache
 * @package Wcs\cache
 */
class NoneCache implements CacheInterface
{
    /**
     * FileCache constructor.
     * @param array $options
     */
    public function __construct(array $options) {}

    /**
     * @param string $key
     * @param array $data
     * @return bool
     */
    public function set(string $key, array $data)
    {
        return true;
    }

    /**
     * @param string $key
     * @return bool
     */
    public function get(string $key)
    {
        return false;
    }

    /**
     * @param string $key
     * @return bool
     */
    public function exist(string $key)
    {
        return false;
    }

    /**
     * @param string $key
     */
    public function remove(string $key) {}
}
