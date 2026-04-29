<?php

use App\Modules\Catalog\Controllers\ProdutosController;

return [
    "GET" => [
        "/produtos" => [ProdutosController::class, "index"],
        "/produtos/criar" => [ProdutosController::class, "create"],
        "/produtos/editar" => [ProdutosController::class, "edit"],
        "/produtos/variacoes" => [ProdutosController::class, "variants"],
        "/produtos/variacoes/editar" => [ProdutosController::class, "getProductVariant"],
    ],
    "POST" => [
        "/produtos" => [ProdutosController::class, "store"],
        "/produtos/update" => [ProdutosController::class, "update"],
        "/produtos/variacoes/cadastrar" => [ProdutosController::class, "storeVariants"],
        "/produtos/variacoes/update" => [ProdutosController::class, "updateVariant"],
    ]
];
