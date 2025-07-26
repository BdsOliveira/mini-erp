<?php

namespace App\Repositories\Stock;

use App\Repositories\BaseRepository;

class GetQtd extends BaseRepository
{
    public function execute(int $variantId): int|bool
    {
        $query = 'SELECT quantidade FROM estoque WHERE variacao_id = :variacao_id LIMIT 1';
        $statement = $this->connection->prepare($query);
        $statement->bindValue('variacao_id', $variantId);
        $statement->execute();
        return $statement->fetchColumn();
    }
}