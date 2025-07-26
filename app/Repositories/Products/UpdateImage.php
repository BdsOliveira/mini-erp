<?php

declare(strict_types=1);

namespace App\Repositories\Products;

use App\Repositories\BaseRepository;

class UpdateImage extends BaseRepository
{
    public function execute(int $productId, string $image): array|bool
    {
        $query = 'UPDATE produtos SET imagem = :imagem WHERE id = :id';
        $statement = $this->connection->prepare($query);
        $statement->bindValue('id', $productId);
        $statement->bindValue('imagem', $image);
        $statement->execute();
        return $statement->fetch();
    }
}