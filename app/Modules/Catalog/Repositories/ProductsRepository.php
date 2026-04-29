<?php

declare(strict_types=1);

namespace App\Modules\Catalog\Repositories;

use App\Modules\Catalog\Models\Product;
use App\Modules\Catalog\Models\Variant;
use App\Modules\Catalog\Repositories\Products\GetPaginated;
use App\Modules\Catalog\Repositories\Products\GetById;
use App\Modules\Catalog\Repositories\Products\Save;
use App\Modules\Catalog\Repositories\Products\SaveVariants;
use App\Modules\Catalog\Repositories\Products\Update;
use App\Modules\Catalog\Repositories\Products\UpdateImage;

class ProductsRepository
{
    private CartsRepository $cartsRepository;
    private VariantsRepository $variantsRepository;

    public function __construct()
    {
        $this->variantsRepository = new VariantsRepository();
        $this->cartsRepository = new CartsRepository();
    }

    public function getPaginated(int $limit = 15, int $page = 1): array
    {
        return (new GetPaginated())->execute($limit, $page);
    }

    public function save(Product $product): int|bool
    {
        return (new Save())->execute(product: $product);
    }

    public function getById(int $id): Product|bool
    {
        return (new GetById())->execute($id);
    }

    public function update(int $productId, Product $product): int|bool
    {
        if (strlen($product->imagem) > 0) {
            $this->updateImage(productId: $productId, image: $product->imagem);
        }
        return (new Update())->execute(productId: $productId, product: $product);
    }

    public function updateImage(int $productId, string $image): int|bool
    {
        return (new UpdateImage())->execute(productId: $productId, image: $image);
    }

    public function getVariants(int $productId): array
    {
        return $this->variantsRepository->getVariantsByProductId($productId);
    }

    public function getVariantById(int $productId, int $variantId): Variant
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

    public function getVariantStockQtd(int $variantId): int|bool//return stock
    {
        return $this->variantsRepository->getStockQtd(variantId: $variantId);
    }

    public function updateVariantStock(int $productId, int $variantId, int $stock): int|bool // return in
    {
        return $this->variantsRepository->updateStock(variantId: $variantId, productId: $productId, stock: $stock);
    }

    public function getCartProducts(array $ids = []): array
    {
        return $this->cartsRepository->getProducts(ids: $ids);
    }
}
