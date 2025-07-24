<?php

declare(strict_types=1);

namespace App\Repositories\Products;

use Framework\Database\Connection;
use PDO;

class GetById
{
    private PDO $connection;

    public function __construct()
    {
        $this->connection = Connection::getInstance();
    }

    public function execute(int $id): array|bool
    {
        $query = 'SELECT * FROM produtos WHERE id = :id LIMIT 1';
        $statement = $this->connection->prepare($query);
        $statement->bindValue('id', $id);
        $statement->execute();
        return $statement->fetch();
    }
}