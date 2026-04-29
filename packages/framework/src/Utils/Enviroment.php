<?php

declare(strict_types=1);

namespace Framework\Utils;

use Dotenv\Dotenv;

class Enviroment
{
    public static function load(): void
    {
        $currentDir = getcwd();
        $dotenv = Dotenv::createImmutable(dirname($currentDir));
        $dotenv->load();
    }
}