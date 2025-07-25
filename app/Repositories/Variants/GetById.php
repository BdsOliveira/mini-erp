<?php

declare(strict_types=1);

namespace App\Repositories\Variants;

use App\Repositories\BaseRepository;

class GetById extends BaseRepository
{
    public function execute(int $productId, int $id): array
    {
        $query = 'SELECT * FROM variacoes WHERE produto_id = :produto_id AND id = :id LIMIT 1';
        $statement = $this->connection->prepare($query);
        $statement->bindValue('produto_id', $productId);
        $statement->bindValue('id', $id);
        $statement->execute();
        return $statement->fetch();
    }
}
