<?php

declare(strict_types=1);

namespace App\Repositories;

use Exception;
use PDO;
use PDOException;

class VariantsRepository extends BaseRepository
{
    public function getVariantsByProductId(int $productId): array
    {
        $query = 'SELECT variacoes.*, estoque.quantidade FROM variacoes
            LEFT JOIN estoque ON variacoes.id = estoque.variacao_id
            WHERE variacoes.produto_id = :produto_id ORDER BY tipo ASC';
        $statement = $this->connection->prepare($query);
        $statement->bindValue('produto_id', $productId);
        $statement->execute();
        return $statement->fetchAll();
    }

    public function getById(int $productId, int $id): array
    {
        $query = 'SELECT * FROM variacoes WHERE produto_id = :produto_id AND id = :id LIMIT 1';
        $statement = $this->connection->prepare($query);
        $statement->bindValue('produto_id', $productId);
        $statement->bindValue('id', $id);
        $statement->execute();
        return $statement->fetch();
    }

    public function save(int $productId, string $type, string $value): int|bool
    {
        $sanitizedValue = str_replace(search: ' ', replace: '-', subject: $value);

        $sku = $productId . '-' . strtolower($type) . '-' . strtolower($sanitizedValue) . '-' . ($this->getLastInsertId(table: "variacoes") + 1);

        $query = 'INSERT INTO variacoes (produto_id, tipo, valor, sku) VALUES (:produto_id, :tipo, :valor, :sku)';
        $statement = $this->connection->prepare($query);
        $statement->bindValue('produto_id', $productId);
        $statement->bindValue('tipo', $type);
        $statement->bindValue('valor', $value);
        $statement->bindValue('sku', $sku);
        $statement->execute();

        return $this->getLastInsertId(table: "variacoes");
    }

    public function update(int $id, int $productId, string $type, string $value): int|bool
    {
        $query = 'UPDATE variacoes SET tipo = :tipo, valor = :valor WHERE produto_id = :produto_id AND id = :id';
        $statement = $this->connection->prepare($query);
        $statement->bindValue('id', $id);
        $statement->bindValue('produto_id', $productId);
        $statement->bindValue('tipo', $type);
        $statement->bindValue('valor', $value);
        $statement->execute();

        return $id;
    }

    public function getStockQtd(int $variantId): int|bool
    {
        $query = 'SELECT quantidade FROM estoque WHERE variacao_id = :variacao_id LIMIT 1';
        $statement = $this->connection->prepare($query);
        $statement->bindValue('variacao_id', $variantId);
        $statement->execute();
        return $statement->fetchColumn();
    }

    public function updateStock(int $variantId, int $productId, int $stock): int|bool
    {
        try {
            $query = 'INSERT INTO estoque (produto_id, variacao_id, quantidade)
                VALUES (:produto_id, :variacao_id, :quantidade)
                ON DUPLICATE KEY UPDATE quantidade = VALUES(quantidade)';

            $statement = $this->connection->prepare($query);
            $statement->bindValue(':produto_id', $productId, PDO::PARAM_INT);
            $statement->bindValue(':variacao_id', $variantId, PDO::PARAM_INT);
            $statement->bindValue(':quantidade', $stock, PDO::PARAM_INT);
            $statement->execute();

            return $this->getLastInsertId(table: "estoque");
        } catch (PDOException $e) {
            throw new Exception("Erro ao atualizar estoque: " . $e->getMessage());
        }
    }
}