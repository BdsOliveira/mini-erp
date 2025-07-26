<?php

declare(strict_types=1);

namespace App\Repositories\Products;

use App\Repositories\BaseRepository;

class Update extends BaseRepository
{
    public function execute(int $productId, string $nome, float $preco, string $descricao): array|bool
    {
        $query = 'UPDATE produtos SET nome = :nome, preco = :preco, descricao = :descricao WHERE id = :id';
        $statement = $this->connection->prepare($query);
        $statement->bindValue('id', $productId);
        $statement->bindValue('nome', $nome);
        $statement->bindValue('preco', $preco);
        $statement->bindValue('descricao', $descricao);
        $statement->execute();
        return $statement->fetch();
    }
}