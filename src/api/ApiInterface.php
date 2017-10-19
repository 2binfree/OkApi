<?php


namespace Wcs\api;


interface ApiInterface
{
    public function connect();
    public function getData(int $method, array $options);
}
