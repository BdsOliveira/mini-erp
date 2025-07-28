<?php

declare(strict_types=1);

namespace App\Repositories\Products;

use App\Models\ProductDTO;
use App\Repositories\BaseRepository;

class GetPaginated extends BaseRepository
{
    public function execute(int $limit = 15, int $page = 1): array
    {
        $query = 'SELECT
            produtos.id,
            produtos.nome,
            produtos.imagem,
            produtos.descricao,
            produtos.preco
        FROM produtos'
            . ($limit ? " LIMIT $limit OFFSET " . (($page - 1) * $limit) : '');
        $products = [];

        $statement = $this->connection->prepare($query);
        $statement->execute();
        while ($row = $statement->fetch()) {
            $products[] = (new ProductDTO($row))->toArray();
        }
        return $products;
    }
}