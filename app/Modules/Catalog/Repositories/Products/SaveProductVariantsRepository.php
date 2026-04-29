<?php

declare(strict_types=1);

namespace App\Modules\Catalog\Repositories\Products;

use App\Modules\Catalog\Repositories\Variants\SaveVariantRepository;
use Exception;

class SaveProductVariantsRepository
{
    public function execute(int $productId, string $type, string $values): int|bool
    {
        $product = (new GetProductByIdRepository())->execute(id: $productId);

        if (!$product) {
            throw new Exception('Produto não encontrado.');
        }

        $sanitizedValues = array_map(callback: 'trim', array: explode(separator: ',', string: $values));

        $result = false;
        $saveVariantRepository = new SaveVariantRepository();
        foreach ($sanitizedValues as $value) {
            $result = $saveVariantRepository->execute(
                productId: $productId,
                type: $type,
                value: $value
            );
        }

        return $result;
    }
}
