<?php

declare(strict_types=1);

namespace App\Repositories;

use Framework\Database\Connection;
use PDO;

class VariantsRepository
{
    private PDO $connection;

    public function __construct()
    {
        $this->connection = Connection::getInstance();
    }

    public function getVariantsByProductId(int $productId): array
    {
        $query = 'SELECT * FROM variacoes WHERE produto_id = :produto_id ORDER BY tipo ASC';
        $statement = $this->connection->prepare($query);
        $statement->bindValue('produto_id', $productId);
        $statement->execute();
        return $statement->fetchAll();
    }

    private function getLastInsertId(): int
    {
        $query = 'SELECT id FROM variacoes ORDER BY id DESC LIMIT 1';
        $statement = $this->connection->prepare($query);
        $statement->execute();
        return $statement->fetchColumn();
    }

    public function save(int $productId, string $type, string $values): int|bool
    {
        $sanitizedValues = array_map(callback: 'trim', array: explode(separator: ',', string: $values));

        if (empty($sanitizedValues)) {
            return false;
        }

        $query = 'INSERT INTO variacoes (produto_id, tipo, valor, sku) VALUES (:produto_id, :tipo, :valor, :sku)';
        $statement = $this->connection->prepare($query);
        $statement->bindValue('produto_id', $productId);
        $statement->bindValue('tipo', $type);
        foreach ($sanitizedValues as $value) {
            $sanitizedValue = str_replace(search: ' ', replace: '-', subject: $value);
            $sku = $productId . '-' . strtolower($type) . '-' . strtolower($sanitizedValue) . '-' . ($this->getLastInsertId() + 1);
            $statement->bindValue('valor', $value);
            $statement->bindValue('sku', $sku);
            $statement->execute();
        }
        return $this->getLastInsertId();
    }
}