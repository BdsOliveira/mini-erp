<?php

declare(strict_types=1);

namespace Framework\Database;

use Exception;
use PDO;
use PDOException;

class Connection
{
    private static $instance = null;

    public static function getInstance(): PDO
    {
        if (self::$instance === null) {
            try {
                self::$instance = new PDO(
                    dsn: Database::getConnectionString(),
                    username: Database::getDatabaseUser(),
                    password: Database::getDatabasePassword(),
                    options: [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                        PDO::ATTR_EMULATE_PREPARES => false,
                    ]
                );
            } catch (PDOException $e) {
                throw new Exception('Erro de conexão com o banco de dados: ' . $e->getMessage());
            }
        }

        return self::$instance;
    }

    private function __construct()
    {
    }

    private function __clone()
    {
    }
}
