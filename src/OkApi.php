<?php

namespace Wcs;


use Wcs\api\Api;
use Wcs\cache\Cache;

class OkApi
{

    /** @var  OkApi */
    private static $instance;

    /** @var resource */
    private $client;

    /** @var  Cache */
    private $cache;



    /** @var  Api */
    private $api;

    /**
     * OkApi constructor.
     */
    private function __construct()
    {
        $this->initCache();
        $this->client = curl_init();
        curl_setopt($this->client, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->client, CURLOPT_USERAGENT,'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13');
    }

    /**
     * @return OkApi
     */
    public static function getInstance()
    {
        if (!is_a(self::$instance, 'Wcs\OkApi')){
            self::$instance = new OkApi();
        }
        return self::$instance;
    }

    /**
     * @param int $method
     * @param array $options
     * @return array|bool
     * @throws \Exception
     */
    public function getData(int $method, array $options = [])
    {
        $url = $this->api->getData($method, $options);
        $data = $this->cache->get($url);
        if (false === $data) {
            curl_setopt($this->client, CURLOPT_URL, $url);
            $output = curl_exec($this->client);
            if (curl_errno($this->client)) {
                throw new \Exception('Erreur Curl : ' . curl_error($this->client));
            }
            $info = curl_getinfo($this->client);
            $data = [
                "data" => $output,
                "info" => $info,
            ];
            $this->cache->set($url, $data);
        }
        return $data;
    }

    /**
     * @param string $cacheType
     * @param array $options
     */
    public function initCache(string $cacheType = "none", array $options = [])
    {
        $this->cache = new Cache($cacheType, $options);
    }

    /**
     * @param string $api
     */
    public function initApi(string $api)
    {
        $this->api = new Api($api);
    }

    /**
     * @return resource
     */
    public function getClient()
    {
        return $this->client;
    }

    public function __destruct()
    {
        curl_close($this->client);
    }
}
