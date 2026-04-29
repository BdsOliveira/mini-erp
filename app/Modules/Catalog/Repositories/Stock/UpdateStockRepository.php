<?php

declare(strict_types=1);

namespace App\Modules\Catalog\Repositories\Stock;

use App\Modules\Core\Base\BaseRepository;

class UpdateStockRepository extends BaseRepository
{
    public function execute(int $variantId, int $productId, int $quantity): int|bool
    {
        $query = 'SELECT id FROM estoque WHERE variacao_id = :variacao_id AND produto_id = :produto_id';
        $statement = $this->connection->prepare($query);
        $statement->bindValue('variacao_id', $variantId);
        $statement->bindValue('produto_id', $productId);
        $statement->execute();
        $id = $statement->fetch();

        if ($id) {
            $query = 'UPDATE estoque SET quantidade = :quantidade WHERE id = :id';
            $statement = $this->connection->prepare($query);
            $statement->bindValue('quantidade', $quantity);
            $statement->bindValue('id', $id['id']);
            $statement->execute();
            return (int) $id['id'];
        }

        $query = 'INSERT INTO estoque (produto_id, variacao_id, quantidade) VALUES (:produto_id, :variacao_id, :quantidade)';
        $statement = $this->connection->prepare($query);
        $statement->bindValue('produto_id', $productId);
        $statement->bindValue('variacao_id', $variantId);
        $statement->bindValue('quantidade', $quantity);
        $statement->execute();
        return (int) $this->connection->lastInsertId();
    }
}
