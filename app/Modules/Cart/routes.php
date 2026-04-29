<?php

use App\Modules\Cart\Controllers\ListCartController;
use App\Modules\Cart\Controllers\AddToCartController;
use App\Modules\Cart\Controllers\ValidateCupomController;
use App\Modules\Cart\Controllers\RemoveCartItemController;

return [
    "GET" => [
        "/carrinho" => [ListCartController::class, "execute"],
    ],
    "POST" => [
        "/carrinho" => [AddToCartController::class, "execute"],
        "/carrinho/validar-cupom" => [ValidateCupomController::class, "execute"],
        "/carrinho/delete-item" => [RemoveCartItemController::class, "execute"],
    ]
];
