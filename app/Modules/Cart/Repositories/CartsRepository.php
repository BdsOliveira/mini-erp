<?php

declare(strict_types=1);

namespace App\Modules\Cart\Repositories;

use App\Modules\Cart\Repositories\Cart\GetProducts;

class CartsRepository
{
    public function getProducts(array $ids = []): array
    {
        return (new GetProducts())->execute($ids);
    }
}
