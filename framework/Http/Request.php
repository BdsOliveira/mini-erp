<?php

declare(strict_types=1);

namespace Framework\Http;

class Request
{
    public static function method(): string
    {
        return $_SERVER['REQUEST_METHOD'] ?? 'GET';
    }

    public static function path(): string
    {
        return parse_url(rtrim($_SERVER['REQUEST_URI'], '/'), PHP_URL_PATH) ?? '/';
    }

    public static function query(): string
    {
        return parse_url($_SERVER['REQUEST_URI'], PHP_URL_QUERY) ?? '';
    }

    public static function get(string $key): string
    {
        return htmlspecialchars($_REQUEST[$key] ?? '', ENT_QUOTES, 'UTF-8');
    }

    public static function all(): array
    {
        $data = [];
        foreach ($_REQUEST as $key => $value) {
            $data[$key] = self::get(key: $key);
        }
        return $data;
    }

    public static function image(string $key): string
    {
        if (!isset($_FILES[$key])) {
            return '';
        }

        if ($_FILES[$key]['error'] !== UPLOAD_ERR_OK) {
            return '';
        }

        return "data:{$_FILES[$key]['type']};base64,{base64_encode(file_get_contents($_FILES[$key]['tmp_name']))}";
    }
}
