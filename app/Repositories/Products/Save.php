<?php

declare(strict_types=1);

namespace App\Repositories\Products;

use App\Models\Product;
use App\Repositories\BaseRepository;
use Exception;
use PDOException;

class Save extends BaseRepository
{
    public function execute(Product $product): int|bool
    {
        try {
            $query = 'INSERT INTO produtos (nome, preco, descricao, imagem) VALUES (:name, :price, :description, :image)';
            $statement = $this->connection->prepare($query);
            $statement->bindValue('name', $product->nome);
            $statement->bindValue('price', $product->preco);
            $statement->bindValue('description', $product->descricao);
            $statement->bindValue('image', $product->imagem);
            $statement->execute();

            return (int) $this->connection->lastInsertId() ?: false;

        } catch (PDOException $e) {
            throw new Exception('Erro ao salvar o produto: ' . $e->getMessage());
        }
    }
}