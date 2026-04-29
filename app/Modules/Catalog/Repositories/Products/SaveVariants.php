<?php

declare(strict_types=1);

namespace App\Modules\Catalog\Repositories\Products;

use App\Modules\Catalog\Repositories\Variants\Save;
use Exception;

class SaveVariants
{
    private Save $saveVariantRepository;

    public function __construct()
    {
        $this->saveVariantRepository = new Save();
    }

    public function execute(int $productId, string $type, string $values): int|bool
    {
        $product = (new GetById())->execute(id: $productId);

        if (!$product) {
            throw new Exception('Produto não encontrado.');
        }

        $sanitizedValues = array_map(callback: 'trim', array: explode(separator: ',', string: $values));

        foreach ($sanitizedValues as $value) {
            $result = $this->saveVariantRepository->execute(
                productId: $productId,
                type: $type,
                value: $value
            );
        }

        return $result;
    }
}