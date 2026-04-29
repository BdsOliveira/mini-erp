<?php

declare(strict_types=1);

namespace App\Modules\Catalog\Repositories\Products;

use App\Modules\Core\Base\BaseRepository;

class UpdateProductImageRepository extends BaseRepository
{
    public function execute(int $productId, string $image): bool
    {
        $query = 'UPDATE produtos SET imagem = :imagem WHERE id = :id';
        $statement = $this->connection->prepare($query);
        $statement->bindValue('id', $productId);
        $statement->bindValue('imagem', $image);
        return $statement->execute();
    }
}
