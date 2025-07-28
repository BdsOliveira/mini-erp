<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Repositories\Cart\GetProducts;

class CartsRepository
{
    public function getProducts(array $ids = []): array
    {
        return (new GetProducts())->execute($ids);
    }
}
