<?php

declare(strict_types=1);

namespace Framework;

class Request
{
    public static function method(): string
    {
        return $_SERVER['REQUEST_METHOD'] ?? 'GET';
    }

    public static function path(): string
    {
        return parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?? '/';
    }

    public static function query(): string
    {
        return parse_url($_SERVER['REQUEST_URI'], PHP_URL_QUERY) ?? '';
    }
}
