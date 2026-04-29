<?php

namespace Framework;

use Framework\Database\Connection;
use Framework\Http\Router;
use Framework\Utils\Environment;
use Framework\Utils\Mail;
use Framework\Utils\Session;

class Application
{
    public static function execute(): void
    {
        Environment::load();
        Connection::getInstance();
        Session::start();
        Mail::config();
        Router::run();
    }
}