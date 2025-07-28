<?php

namespace Framework\Utils;

class Session
{
    public static function start()
    {
        session_start();
    }

    public static function destroy()
    {
        session_destroy();
    }

    public static function set(string $key, $value)
    {
        $_SESSION[$key] = $value;
    }

    public static function get(string $key)
    {
        return $_SESSION[$key] ?? null;
    }

    public static function remove(string $key, int $index = -1)
    {
        if ($index >= 0) {
            unset($_SESSION[$key][$index]);
            return;
        }
        unset($_SESSION[$key]);
    }

    public static function has(string $key)
    {
        return isset($_SESSION[$key]);
    }

    public static function push(string $key, mixed $value)
    {
        $_SESSION[$key][] = $value;
    }

    public static function count(string $key)
    {
        return count($_SESSION[$key]);
    }

    public static function clear()
    {
        $_SESSION = [];
    }

    public static function all()
    {
        return $_SESSION;
    }

    public static function flash(string $value): void
    {
        self::set(key: 'flash', value: $value);
    }

    public static function getFlash()
    {
        $flash = self::get(key: 'flash');
        self::remove(key: 'flash');
        return $flash;
    }
}
