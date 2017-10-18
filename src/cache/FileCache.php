<?php
/**
 * Copyright (c) 2017. Lorem ipsum dolor sit amet, consectetur adipiscing elit.
 * Morbi non lorem porttitor neque feugiat blandit. Ut vitae ipsum eget quam lacinia accumsan.
 * Etiam sed turpis ac ipsum condimentum fringilla. Maecenas magna.
 * Proin dapibus sapien vel ante. Aliquam erat volutpat. Pellentesque sagittis ligula eget metus.
 * Vestibulum commodo. Ut rhoncus gravida arcu.
 */

/**
 * Created by PhpStorm.
 * User: ubuntu
 * Date: 17/10/17
 * Time: 17:42
 */

namespace Wcs\cache;


class FileCache implements CacheInterface
{
    private $cachePath;

    /**
     * FileCache constructor.
     * @param array $options
     * @throws \Exception
     */
    public function __construct(array $options)
    {
        if (!isset($options["cache-path"])){
            throw new \Exception("Missing option 'cache-path' for cache file");
        }
        $this->cachePath = $options["cache-path"];
    }

    /**
     * @param string $key
     * @param array $data
     * @return bool|int
     */
    public function set(string $key, array $data)
    {
        $data = serialize($data);
        return file_put_contents($this->cachePath . "/" . $key, $data);
    }

    /**
     * @param string $key
     * @return bool|mixed
     */
    public function get(string $key)
    {
        if ($this->exist($key)){
            $data = file_get_contents($this->cachePath . "/" . $key);
            return unserialize($data);
        }
        return false;
    }

    /**
     * @param string $key
     * @return bool
     */
    public function exist(string $key)
    {
        if (file_exists($this->cachePath . "/" . $key)){
            return true;
        }
        return false;
    }

    public function remove(string $key)
    {
        unlink($this->cachePath . "/" . $key);
    }
}