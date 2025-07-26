<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Repositories\Products\GetPaginated;
use App\Repositories\Products\GetById;
use App\Repositories\Products\Save;
use App\Repositories\Products\SaveVariants;
use App\Repositories\Products\Update;
use App\Repositories\Products\UpdateImage;

class ProductsRepository extends BaseRepository
{
    private VariantsRepository $variantsRepository;

    public function __construct()
    {
        parent::__construct();
        $this->variantsRepository = new VariantsRepository();
    }

    public function getPaginated(int $limit = 15, int $page = 1): array
    {
        return (new GetPaginated())->execute($limit, $page);
    }

    public function save(string $name, float $price, string $description, ?string $image): int|bool
    {
        return (new Save())->execute($name, $price, $description, $image);
    }

    public function getById(int $id): array|bool
    {
        return (new GetById())->execute($id);
    }

    public function update(int $productId, string $nome, float $preco, string $descricao, ?string $image = ''): int|bool
    {
        if (strlen($image) > 0) {
            $this->updateImage(productId: $productId, image: $image);
        }
        return (new Update())->execute(productId: $productId, nome: $nome, preco: $preco, descricao: $descricao);
    }

    public function updateImage(int $productId, string $image): int|bool
    {
        return (new UpdateImage())->execute(productId: $productId, image: $image);
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

    public function updateVariant(int $productId, int $variantId, string $type, string $value): int|bool
    {
        return $this->variantsRepository->update(id: $variantId, productId: $productId, type: $type, value: $value);
    }

    public function getVariantStockQtd(int $variantId): int|bool
    {
        return $this->variantsRepository->getStockQtd(variantId: $variantId);
    }

    public function updateVariantStock(int $productId, int $variantId, int $stock): int|bool
    {
        return $this->variantsRepository->updateStock(variantId: $variantId, productId: $productId, stock: $stock);
    }
}