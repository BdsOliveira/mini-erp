<?php

namespace Framework;

use Framework\Database\Connection;
use Framework\Http\Router;
use Framework\Utils\Enviroment;
use Framework\Utils\Mail;
use Framework\Utils\Session;

class Aplicattion
{
    public static function execute(): void
    {
        Enviroment::load();
        Connection::getInstance();
        Session::start();
        Mail::config();
        Router::run();
    }
}