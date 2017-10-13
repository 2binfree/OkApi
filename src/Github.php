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

    public function getUserInfos()
    {
        $url = self::BASE_URL . "/users/" . $this->app->getLogin();
        return $this->app->getData($url);
    }
}