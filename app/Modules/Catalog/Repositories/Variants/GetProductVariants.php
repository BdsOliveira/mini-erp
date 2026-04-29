<?php

declare(strict_types=1);

namespace App\Modules\Catalog\Repositories\Variants;

use App\Modules\Core\Base\BaseRepository;

class GetProductVariants extends BaseRepository
{
    public function execute(int $productId): array
    {
        $query = 'SELECT variacoes.*, estoque.quantidade FROM variacoes
            LEFT JOIN estoque ON variacoes.id = estoque.variacao_id
            WHERE variacoes.produto_id = :produto_id ORDER BY tipo ASC';
        $statement = $this->connection->prepare($query);
        $statement->bindValue('produto_id', $productId);
        $statement->execute();
        return $statement->fetchAll();
    }
}
