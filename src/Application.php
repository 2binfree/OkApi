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
    /** @var  Application */
    private static $instance;

    /** @var resource */
    private $client;

    private $githubId;

    private function __construct()
    {
        $credentials = include(__DIR__ . "/../config/credential.php");
        $username = $credentials["username"];
        $password = $credentials["password"];
        $this->githubId = $username;
        $this->client = curl_init();
        curl_setopt($this->client, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->client, CURLOPT_USERPWD, "$username:$password");
        curl_setopt($this->client, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($this->client, CURLOPT_HEADER, 1);
        curl_setopt($this->client, CURLOPT_USERAGENT,'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13');
    }

    public static function getInstance()
    {
        if (!is_a(self::$instance, 'Wcs\Application')){
            self::$instance = new Application();
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
    public function getGithubId()
    {
        return $this->githubId;
    }

    public function __destruct()
    {
        curl_close($this->client);
    }
}
