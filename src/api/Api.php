<?php

namespace Wcs\api;

use Symfony\Component\Yaml\Yaml;

class Api implements ApiInterface
{
    /** @var  ApiInterface */
    private $api;

    /** @var bool  */
    private $connected = false;

    /**
     * Api constructor.
     * @param string $api
     * @throws \Exception
     */
    public function __construct(string $api)
    {
        $credentials = $this->getCredentials($api);
        $className = ucfirst($api) . "Api";
        $class = "Wcs\\api\\" . $className;
        if (file_exists(__DIR__ . '/' . $className . '.php')){
            $this->api = new $class($credentials);
            if (!is_a($this->api, "Wcs\api\ApiInterface")){
                throw new \Exception("Api class must implement ApiInterface");
            }
        } else {
            throw new \Exception("Api '$api' is not supported");
        }
        $this->connect();

    }

    /**
     * @param string $api
     * @return array
     * @throws \Exception
     */
    private function getCredentials(string $api)
    {
        $data = Yaml::parse(file_get_contents(__DIR__ . '/../../config/credentials.yml'));
        if (isset($data[$api])){
            return $data[$api];
        } else {
            throw new \Exception("Credentials for '$api' not found");
        }
    }

    public function connect()
    {
        $this->api->connect();
        $this->connected = true;
    }

    /**
     * @return bool
     */
    public function isConnected()
    {
        return $this->connected;
    }

    /**
     * @param int $method
     * @param array $options
     * @return string
     */
    public function getData(int $method, array $options)
    {
        return $this->api->getData($method, $options);
    }
}
