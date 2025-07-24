<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Repositories\Products\GetAll;
use App\Repositories\Products\GetById;
use App\Repositories\Products\Save;
use App\Repositories\Products\SaveVariants;

class ProductsRepository
{
    private VariantsRepository $variantsRepository;

    public function __construct()
    {
        $this->variantsRepository = new VariantsRepository();
    }

    public function getAll(int $limit = 15, int $page = 1): array
    {
        return (new GetAll())->execute($limit, $page);
    }

    public function save(string $name, float $price, string $description, ?string $image): int|bool
    {
        return (new Save())->execute($name, $price, $description, $image);
    }

    public function getById(int $id): array|bool
    {
        return (new GetById())->execute($id);
    }

    public function getVariants(int $productId): array
    {
        return $this->variantsRepository->getVariantsByProductId($productId);
    }

    public function getVariantById(int $productId, int $variantId): array
    {
        return $this->variantsRepository->getById($productId, $variantId);
    }

    public function saveVariants(int $productId, string $type, string $values): int|bool
    {
        return (new SaveVariants())->execute($productId, $type, $values);
    }

    public function updateVariant(int $productId, int $variantId, string $type, string $value): void
    {
        $this->variantsRepository->update(id: $variantId, productId: $productId, type: $type, value: $value);
    }
}