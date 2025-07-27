<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Variant;
use App\Repositories\Variants\GetById;
use App\Repositories\Variants\GetProductVariants;
use App\Repositories\Variants\Save;
use App\Repositories\Variants\Update;

class VariantsRepository
{
    private StockRepository $stockRepository;

    public function __construct()
    {
        $this->stockRepository = new StockRepository();
    }
    public function getVariantsByProductId(int $productId): array
    {
        return (new GetProductVariants())->execute($productId);
    }

    public function getById(int $productId, int $id): Variant
    {
        return (new GetById())->execute($productId, $id);
    }

    public function save(int $productId, string $type, string $value): int|bool
    {
        return (new Save())->execute($productId, $type, $value);
    }

    public function update(int $id, int $productId, string $type, string $value): int|bool
    {
        return (new Update())->execute($id, $productId, $type, $value);
    }

    public function getStockQtd(int $variantId): int|bool
    {
        return $this->stockRepository->getQtd($variantId);
    }

    public function updateStock(int $variantId, int $productId, int $stock): int|bool
    {
        return $this->stockRepository->update($variantId, $productId, $stock);
    }
}
