<?php

declare(strict_types=1);

namespace Framework\Database;

use Exception;

class Database
{
    /** @var array<string, mixed>|null */
    private static ?array $config = null;
    private static ?string $connection = null;

    private static function loadConfig(): void
    {
        if (self::$config === null) {
            $root = dirname(getcwd());
            self::$config = include $root . '/config/database.php';
            self::$connection = $_ENV['DB_CONNECTION'] ?? self::$config['default'];
        }
    }

    public static function getConnectionString(): string
    {
        self::loadConfig();
        $host = self::$config['connections'][self::$connection]['host'];
        $port = self::$config['connections'][self::$connection]['port'];
        $database = self::$config['connections'][self::$connection]['database'];
        $charset = self::$config['connections'][self::$connection]['charset'];

        if (empty(self::$connection) || empty($host) || empty($port) || empty($database) || empty($charset)) {
            throw new Exception('Configuracoes de banco de dados incompletas.');
        }
        return sprintf(
            '%s:host=%s;port=%s;dbname=%s;charset=%s',
            self::$connection,
            $host,
            $port,
            $database,
            $charset
        );
    }

    public static function getDatabaseUser(): string
    {
        self::loadConfig();
        return self::$config['connections'][self::$connection]['username'];
    }

    public static function getDatabasePassword(): string
    {
        self::loadConfig();
        return self::$config['connections'][self::$connection]['password'];
    }
}
