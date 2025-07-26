<?php

namespace App\Repositories\Stock;

use App\Repositories\BaseRepository;
use Exception;
use PDO;
use PDOException;

class Update extends BaseRepository
{
    public function execute(int $variantId, int $productId, int $quantity): int|bool
    {
        try {
            $query = 'INSERT INTO estoque (produto_id, variacao_id, quantidade)
                VALUES (:produto_id, :variacao_id, :quantidade)
                ON DUPLICATE KEY UPDATE quantidade = VALUES(quantidade)';

            $statement = $this->connection->prepare($query);
            $statement->bindValue(':produto_id', $productId, PDO::PARAM_INT);
            $statement->bindValue(':variacao_id', $variantId, PDO::PARAM_INT);
            $statement->bindValue(':quantidade', $quantity, PDO::PARAM_INT);
            $statement->execute();

            return $this->getLastInsertId(table: "estoque");
        } catch (PDOException $e) {
            throw new Exception("Erro ao atualizar estoque: " . $e->getMessage());
        }
    }
}