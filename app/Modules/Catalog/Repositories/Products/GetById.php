<?php

declare(strict_types=1);

namespace App\Modules\Catalog\Repositories\Products;

use App\Modules\Catalog\Models\Product;
use App\Modules\Catalog\DTOs\ProductDTO;
use App\Modules\Core\Base\BaseRepository;

class GetById extends BaseRepository
{
    public function execute(int $id): Product|bool
    {
        $query = 'SELECT * FROM produtos WHERE id = :id LIMIT 1';
        $statement = $this->connection->prepare($query);
        $statement->bindValue('id', $id);
        $statement->execute();
        return (new ProductDTO($statement->fetch()))->fromArray();
    }
}