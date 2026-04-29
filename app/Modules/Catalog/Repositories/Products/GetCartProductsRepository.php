<?php

declare(strict_types=1);

namespace App\Modules\Catalog\Repositories\Products;

use App\Modules\Catalog\DTOs\ProductDTO;
use App\Modules\Core\Base\BaseRepository;

class GetCartProductsRepository extends BaseRepository
{
    public function execute(array $ids = []): array
    {
        $ids = array_map('intval', $ids);
        if (empty($ids)) {
            return [];
        }

        $query = 'SELECT
            produtos.id,
            produtos.nome,
            produtos.imagem,
            produtos.descricao,
            produtos.preco
        FROM produtos
        WHERE produtos.status = 1 AND
        produtos.id IN (' . implode(',', $ids) . ')';
        $products = [];

        $statement = $this->connection->prepare($query);
        $statement->execute();
        while ($row = $statement->fetch()) {
            $products[] = (new ProductDTO($row))->toArray();
        }
        return $products;
    }
}
