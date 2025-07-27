<?php

declare(strict_types=1);

namespace App\Repositories\Products;

use App\Models\Product;
use App\Models\ProductDTO;
use App\Repositories\BaseRepository;

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