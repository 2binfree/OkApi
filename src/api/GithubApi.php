<?php

namespace Wcs\api;

use Wcs\OkApi;

class GithubApi implements ApiInterface
{
    const GITHUB_USER = 1;

    const METHODS = [
        self::GITHUB_USER => "/users/",
    ];

    /** @var  array */
    private $credentials;

    /**
     * GithubApi constructor.
     * @param array $credentials
     * @throws \Exception
     */
    public function __construct(array $credentials)
    {
        if (!isset($credentials['base_url'])){
            throw new \Exception("Base URL is not defined for Github API");
        }
        $this->credentials = $credentials;
    }

    /**
     * @throws \Exception
     */
    public function connect()
    {
        $okapi = OkApi::getInstance();
        if (!isset($this->credentials['auth_method'])) {
            throw new \Exception("auth_method not defined for github api");
        }
        switch ($this->credentials['auth_method']) {
            case 'basic':
                if (!isset($this->credentials["user"])) {
                    throw new \Exception("Github user is not defined");
                }
                if (!isset($this->credentials["password"])) {
                    throw new \Exception("Github password is not defined");
                }
                $user = $this->credentials["user"];
                $password = $this->credentials["password"];
                curl_setopt($okapi->getClient(), CURLOPT_USERPWD, "$user:$password");
                curl_setopt($okapi->getClient(), CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
                break;
            case 'token':
                if (!isset($this->credentials["token"])) {
                    throw new \Exception("Github token is not defined");
                }
                $token = $this->credentials["token"];
                curl_setopt($okapi->getClient(), CURLOPT_HTTPHEADER, ["Authorization: token $token"]);
                break;
            default:
                throw new \Exception("Github auth_method " . $this->credentials['auth_method'] . "is not supported");
        }
    }

    /**
     * @param int $method
     * @param array $options
     * @return string
     * @throws \Exception
     */
    public function getData(int $method, array $options)
    {
        if (!isset(self::METHODS[$method])){
            throw new \Exception('Github api method is not defined');
        }
        switch ($method){
            case self::GITHUB_USER:
                $user = "";
                if (isset($this->credentials['user'])){
                    $user = $this->credentials['user'];
                }
                if (isset($options['user'])){
                    $user = $options['user'];
                }
                if ($user === ""){
                    throw new \Exception('Github user is not defined');
                }
                return $this->credentials['base_url'] . self::METHODS[self::GITHUB_USER] . $user;
                break;
        }
    }

}
