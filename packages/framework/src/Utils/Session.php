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
            $i = array_find_key(array: $_SESSION['cart'], callback: fn ($value) => $value == $index);
            unset($_SESSION[$key][$i]);
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
        if (in_array(needle: $value, haystack: $_SESSION[$key] ?? [])) {
            return;
        }

        $_SESSION[$key][] = $value;
    }

    public static function count(string $key)
    {
        return count($_SESSION[$key] ?? []);
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
