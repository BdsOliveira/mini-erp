<?php

declare(strict_types=1);

namespace App\Repositories\Products;

use App\Repositories\BaseRepository;
use Exception;
use PDOException;

class Save extends BaseRepository
{
    public function execute(string $name, float $price, string $description, ?string $image): int|bool
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