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
    private VariantsRepository $variantsRepository;

    public function __construct()
    {
        $this->connection = Connection::getInstance();
        $this->variantsRepository = new VariantsRepository();
    }

    public function getAll(int $limit = 15, int $page = 1): array
    {
        $query = 'SELECT
            produtos.id,
            produtos.nome,
            produtos.preco
        FROM produtos'
            . ($limit ? " LIMIT $limit OFFSET " . (($page - 1) * $limit) : '');
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

    public function getById(int $id): array|bool
    {
        $query = 'SELECT * FROM produtos WHERE id = :id LIMIT 1';
        $statement = $this->connection->prepare($query);
        $statement->bindValue('id', $id);
        $statement->execute();
        return $statement->fetch();
    }

    public function getVariants(int $productId): array
    {
        return $this->variantsRepository->getVariantsByProductId($productId);
    }
    
    public function saveVariants(int $productId, string $type, string $values): int|bool
    {
        $product = $this->getById($productId);

        if (!$product) {
            throw new Exception('Produto não encontrado.');
        }

        return $this->variantsRepository->save(
            productId: $productId,
            type: $type,
            values: $values
        );
    }
}