<?php

declare(strict_types=1);

namespace App\Modules\Catalog\Repositories\Stock;

use App\Modules\Core\Base\BaseRepository;

class GetStockQuantityRepository extends BaseRepository
{
    public function execute(int $variantId): int|bool
    {
        $query = 'SELECT quantidade FROM estoque WHERE variacao_id = :variacao_id';
        $statement = $this->connection->prepare($query);
        $statement->bindValue('variacao_id', $variantId);
        $statement->execute();
        $result = $statement->fetch();
        return $result ? (int) $result['quantidade'] : false;
    }
}
