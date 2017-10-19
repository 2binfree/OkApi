<?php

namespace Wcs\cache;

class Cache
{
    /** @var  CacheInterface */
    private $cache;

    /** @var  int */
    private $delay;

    /**
     * Cache constructor.
     * options :
     *          cache-path : directory for file cache (file type ony)
     *          delay : seconds of retention (all types), 3600 seconds default
     * @param string $type
     * @param array $options
     * @throws \Exception
     */
    public function __construct(string $type = "none", array $options = [])
    {
        $this->delay = 3600;
        if (isset($options['delay'])){
            $delay = $options['delay'];
            if (is_int($delay) and $delay > 0) {
                $this->delay = $delay;
            } else {
                throw new \Exception("Invalid cache delay value '$delay'");
            }
        }
        $className = ucfirst($type) . "Cache";
        $class = "Wcs\\cache\\" . $className;
        if (file_exists(__DIR__ . '/' . $className . '.php')){
            $this->cache = new $class($options);
            if (!is_a($this->cache, "Wcs\cache\CacheInterface")){
                throw new \Exception("Cache class must implement CacheInterface");
            }
        } else {
            throw new \Exception("Cache type $type is not supported");
        }
    }

    /**
     * @param string $key
     * @return array | bool
     */
    public function get(string $key)
    {
        $key = md5($key);
        if (false != ($data = $this->cache->get($key))){
            $timeStamp = array_shift($data);
            $now = new \DateTime();
            $delay = $now->getTimestamp() - $timeStamp['___creation_time___'];
            if ($delay < $this->delay) {
                return $data;
            } else {
                $this->remove($key);
            }
        }
        return false;
    }

    /**
     * @param string $key
     * @param array $data
     * @return bool
     */
    public function set(string $key, array $data)
    {
        $key = md5($key);
        $now = new \DateTime();
        array_unshift($data, ['___creation_time___' => $now->getTimeStamp()]);
        return $this->cache->set($key, $data);
    }

    /**
     * @param string $key
     * @return bool
     */
    public function exist(string $key)
    {
        return $this->exist($key);
    }

    /**
     * @param string $key
     */
    public function remove($key)
    {
        $this->cache->remove($key);
    }
}