<?php

namespace Wcs\cache;

interface CacheInterface
{
    public function __construct(array $options);
    public function set(string $key, array $data);
    public function get(string $key);
    public function exist(string $key);
    public function remove(string $key);
}
