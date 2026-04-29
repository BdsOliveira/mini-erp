<?php

declare(strict_types=1);

namespace App\Modules\Catalog\Repositories;

use App\Modules\Catalog\Repositories\Stock\GetQtd;
use App\Modules\Catalog\Repositories\Stock\Update;

class StockRepository
{
    public function getQtd(int $variantId): int|bool
    {
        return (new GetQtd())->execute($variantId);
    }

    public function update(int $variantId, int $productId, int $quantity): int|bool
    {
        return (new Update())->execute($variantId, $productId, $quantity);
    }
}
