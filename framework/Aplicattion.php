<?php

namespace Framework;

use Framework\Database\Connection;
use Framework\Http\Router;
use Framework\Utils\Enviroment;

class Aplicattion
{
    public static function execute(): void
    {
        Enviroment::load();
        Connection::getInstance();
        Router::run();
    }
}