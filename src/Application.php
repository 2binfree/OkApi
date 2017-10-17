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
 * Date: 12/10/17
 * Time: 14:56
 */

namespace Wcs;


class Application
{
    const AUTH_METHOD_BASIC = 1;
    const AUTH_METHOD_TOKEN = 2;

    /** @var  Application */
    private static $instance;

    /** @var resource */
    private $client;

    /** @var  string */
    private $login;

    /** @var  @var int */
    private $authMethod;

    private function __construct($authMethod = self::AUTH_METHOD_BASIC)
    {
        $credentials = include(__DIR__ . "/../config/credential.php");
        $this->setAuthMethod($authMethod);
        $login = $credentials["username"];
        $password = $credentials["password"];
        $token = $credentials["token"];
        $this->login = $login;
        $this->client = curl_init();
        if ($this->authMethod === self::AUTH_METHOD_BASIC) {
            curl_setopt($this->client, CURLOPT_USERPWD, "$login:$password");
            curl_setopt($this->client, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        } elseif ($this->authMethod === self::AUTH_METHOD_TOKEN) {
            curl_setopt($this->client, CURLOPT_HTTPHEADER, ["Authorization: token $token"]);
        }
        curl_setopt($this->client, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->client, CURLOPT_USERAGENT,'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13');
    }

    public static function getInstance($authMethod = self::AUTH_METHOD_BASIC)
    {
        if (!is_a(self::$instance, 'Wcs\Application')){
            self::$instance = new Application($authMethod);
        }
        return self::$instance;
    }

    public function getData($url)
    {
        curl_setopt($this->client, CURLOPT_URL, $url);
        $output = curl_exec($this->client);
        if(curl_errno($this->client))
        {
            throw new \Exception('Erreur Curl : ' . curl_error($this->client));
        }
        $info = curl_getinfo($this->client);
        return [
          "data" => $output,
          "info" => $info,
        ];
    }

    /**
     * @return string
     */
    public function getLogin()
    {
        return $this->login;
    }

    /**
     * @param int $method
     * @return $this
     */
    public function setAuthMethod($method)
    {
        $this->authMethod = $method;
        return $this;
    }

    public function __destruct()
    {
        curl_close($this->client);
    }
}
