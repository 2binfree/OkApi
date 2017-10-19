<?php

namespace Wcs;

use Wcs\api\GithubApi;

require_once __DIR__ . "/../vendor/autoload.php";

$api = OkApi::getInstance();
$api->initCache('file', [
    "cache-path" => __DIR__ . '/../cache/',
    "delay"      => 60,
]);
$api
    ->initApi("github");
var_dump($api->getData(GithubApi::GITHUB_USER));


