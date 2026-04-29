<?php

declare(strict_types=1);

namespace App\Modules\Catalog\Repositories\Products;

use App\Modules\Catalog\Models\Product;
use App\Modules\Core\Base\BaseRepository;

class Update extends BaseRepository
{
    public function execute(int $productId, Product $product): array|bool
    {
        $query = 'UPDATE produtos SET nome = :nome, preco = :preco, descricao = :descricao WHERE id = :id';
        $statement = $this->connection->prepare($query);
        $statement->bindValue('id', $productId);
        $statement->bindValue('nome', $product->nome);
        $statement->bindValue('preco', $product->preco);
        $statement->bindValue('descricao', $product->descricao);
        $statement->execute();
        return $statement->fetch();
    }
}