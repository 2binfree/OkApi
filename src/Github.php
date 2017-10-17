<?php


namespace Wcs;


class Github
{
    const BASE_URL = "https://api.github.com";

    private $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    public function getUserInfos($user = null)
    {
        if (is_null($user)){
            $user = $this->app->getLogin();
        }
        $url = self::BASE_URL . "/users/" . $user;
        return $this->app->getData($url);
    }
}