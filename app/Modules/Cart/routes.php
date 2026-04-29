<?php

use App\Modules\Cart\Controllers\CarrinhoController;

return [
    "GET" => [
        "/carrinho" => [CarrinhoController::class, "index"],
    ],
    "POST" => [
        "/carrinho" => [CarrinhoController::class, "addToCart"],
        "/carrinho/validar-cupom" => [CarrinhoController::class, "validateCupom"],
        "/carrinho/delete-item" => [CarrinhoController::class, "deleteItem"],
    ]
];
