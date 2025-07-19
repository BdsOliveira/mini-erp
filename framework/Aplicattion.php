<?php

namespace Framework;

use Framework\Http\Router;
use Framework\Utils\Enviroment;

class Aplicattion
{
    public static function execute(): void
    {
        Enviroment::load();
        Router::run();
    }
}