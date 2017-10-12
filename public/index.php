<?php

namespace Wcs;

require_once __DIR__ . "/../vendor/autoload.php";

$app = Application::getInstance();

$github = new Github($app);

var_dump($github->getUserInfos());


