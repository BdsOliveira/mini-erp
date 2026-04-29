<?php

namespace Framework\Utils;

class Session
{
    public static function start(): void
    {
        session_start();
    }

    public static function destroy(): void
    {
        session_destroy();
    }

    public static function set(string $key, mixed $value): void
    {
        $_SESSION[$key] = $value;
    }

    public static function get(string $key): mixed
    {
        return $_SESSION[$key] ?? null;
    }

    public static function remove(string $key, int $index = -1): void
    {
        if ($index >= 0) {
            $i = array_find_key(array: $_SESSION['cart'], callback: fn ($value) => $value == $index);
            unset($_SESSION[$key][$i]);
            return;
        }
        unset($_SESSION[$key]);
    }

    public static function has(string $key): bool
    {
        return isset($_SESSION[$key]);
    }

    public static function push(string $key, mixed $value): void
    {
        if (in_array(needle: $value, haystack: $_SESSION[$key] ?? [])) {
            return;
        }

        $_SESSION[$key][] = $value;
    }

    public static function count(string $key): int
    {
        return count($_SESSION[$key] ?? []);
    }

    public static function clear(): void
    {
        $_SESSION = [];
    }

    /** @return array<string, mixed> */
    public static function all(): array
    {
        return $_SESSION;
    }

    public static function flash(string $value): void
    {
        self::set(key: 'flash', value: $value);
    }

    public static function getFlash(): mixed
    {
        $flash = self::get(key: 'flash');
        self::remove(key: 'flash');
        return $flash;
    }
}
