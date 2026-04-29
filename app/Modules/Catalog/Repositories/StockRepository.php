<?php

declare(strict_types=1);

namespace App\Modules\Catalog\Repositories;

use App\Modules\Catalog\Repositories\Stock\GetStockQuantityRepository;
use App\Modules\Catalog\Repositories\Stock\UpdateStockRepository;

class StockRepository
{
    public function getQtd(int $variantId): int|bool
    {
        return (new GetStockQuantityRepository())->execute($variantId);
    }

    public function update(int $variantId, int $productId, int $quantity): int|bool
    {
        return (new UpdateStockRepository())->execute($variantId, $productId, $quantity);
    }
}
