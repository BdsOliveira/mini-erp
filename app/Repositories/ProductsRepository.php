<?php

declare(strict_types=1);

namespace App\Repositories;

use Exception;
use Framework\Database\Connection;
use PDO;
use PDOException;

class ProductsRepository
{
    private PDO $connection;

    public function __construct()
    {
        $this->connection = Connection::getInstance();
    }

    public function getAll(): array
    {
        $query = 'SELECT
            produtos.id,
            produtos.nome,
            produtos.preco
        FROM produtos';
        $products = [];

        $statement = $this->connection->prepare($query);
        $statement->execute();
        while ($row = $statement->fetch()) {
            $products[] = $row;
        }
        return $products;
    }

    public function save(string $name, float $price, string $description, ?string $image): int|bool
    {
        try {
            $query = 'INSERT INTO produtos (nome, preco, descricao, imagem) VALUES (:name, :price, :description, :image)';
            $statement = $this->connection->prepare($query);
            $statement->bindValue('name', $name);
            $statement->bindValue('price', $price);
            $statement->bindValue('description', $description);
            $statement->bindValue('image', $image);
            $statement->execute();

            return (int) $this->connection->lastInsertId() ?: false;

        } catch (PDOException $e) {
            throw new Exception('Erro ao salvar o produto: ' . $e->getMessage());
        }
    }
}