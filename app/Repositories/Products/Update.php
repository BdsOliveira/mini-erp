<?php

declare(strict_types=1);

namespace App\Repositories\Products;

use App\Models\Product;
use App\Repositories\BaseRepository;

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