<?php

declare(strict_types=1);

namespace App\Repositories\Products;

use App\Repositories\VariantsRepository;
use Exception;

class SaveVariants
{
    private VariantsRepository $variantsRepository;

    public function __construct()
    {
        $this->variantsRepository = new VariantsRepository();
    }

    public function execute(int $productId, string $type, string $values): int|bool
    {
        $product = (new GetById())->execute(id: $productId);

        if (!$product) {
            throw new Exception('Produto não encontrado.');
        }

        $sanitizedValues = array_map(callback: 'trim', array: explode(separator: ',', string: $values));

        foreach ($sanitizedValues as $value) {
            $result = $this->variantsRepository->save(
                productId: $productId,
                type: $type,
                value: $value
            );
        }

        return $result;
    }
}