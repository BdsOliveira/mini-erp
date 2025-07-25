<?php

declare(strict_types=1);

namespace App\Repositories;

use Framework\Database\Connection;
use PDO;

abstract class BaseRepository
{
    protected PDO $connection;

    public function __construct()
    {
        $this->connection = Connection::getInstance();
    }

    protected function getLastInsertId(string $table): int|bool
    {
        $query = "SELECT id FROM $table ORDER BY id DESC LIMIT 1";
        $statement = $this->connection->prepare($query);
        $statement->execute();
        return $statement->fetchColumn();
    }
}