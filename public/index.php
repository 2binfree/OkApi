<?php

namespace Wcs;

require_once __DIR__ . "/../vendor/autoload.php";

$app = Application::getInstance(Application::AUTH_METHOD_TOKEN);
$app->initCache('file', [
    "cache-path" => __DIR__ . '/../cache/',
    "delay"      => 60,
]);
$github = new Github($app);

var_dump($github->getUserInfos()['data']);


