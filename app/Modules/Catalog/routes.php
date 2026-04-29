<?php

use App\Modules\Catalog\Controllers\ListProductsController;
use App\Modules\Catalog\Controllers\StoreProductController;
use App\Modules\Catalog\Controllers\EditProductController;
use App\Modules\Catalog\Controllers\UpdateProductController;
use App\Modules\Catalog\Controllers\ListProductVariantsController;
use App\Modules\Catalog\Controllers\StoreProductVariantsController;
use App\Modules\Catalog\Controllers\EditProductVariantController;
use App\Modules\Catalog\Controllers\UpdateProductVariantController;

return [
    "GET" => [
        "/produtos" => [ListProductsController::class, "execute"],
        "/produtos/editar" => [EditProductController::class, "execute"],
        "/produtos/variacoes" => [ListProductVariantsController::class, "execute"],
        "/produtos/variacoes/editar" => [EditProductVariantController::class, "execute"],
    ],
    "POST" => [
        "/produtos" => [StoreProductController::class, "execute"],
        "/produtos/update" => [UpdateProductController::class, "execute"],
        "/produtos/variacoes/cadastrar" => [StoreProductVariantsController::class, "execute"],
        "/produtos/variacoes/update" => [UpdateProductVariantController::class, "execute"],
    ]
];
