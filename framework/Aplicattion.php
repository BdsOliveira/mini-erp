<?php

namespace Framework;

use Framework\Http\Router;

class Aplicattion
{
    public static function execute(): void
    {
        Router::run();
    }
}