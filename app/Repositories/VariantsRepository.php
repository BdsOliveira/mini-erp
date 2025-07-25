<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Repositories\Variants\GetById;
use App\Repositories\Variants\GetProductVariants;
use App\Repositories\Variants\Save;
use App\Repositories\Variants\Update;
use Exception;
use PDO;
use PDOException;

class VariantsRepository extends BaseRepository
{
    public function getVariantsByProductId(int $productId): array
    {
        return (new GetProductVariants())->execute($productId);
    }

    public function getById(int $productId, int $id): array
    {
        return (new GetById())->execute($productId, $id);
    }

    public function save(int $productId, string $type, string $value): int|bool
    {
        return (new Save())->execute($productId, $type, $value);
    }

    public function update(int $id, int $productId, string $type, string $value): int|bool
    {
        return (new Update())->execute($id, $productId, $type, $value);
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