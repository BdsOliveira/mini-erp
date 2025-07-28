<?php

declare(strict_types=1);

namespace App\Repositories\Cart;

use App\Models\ProductDTO;
use App\Repositories\BaseRepository;

class GetProducts extends BaseRepository
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