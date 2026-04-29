<?php

use App\Modules\Checkout\Controllers\CheckoutController;
use App\Modules\Checkout\Controllers\PedidosController;

return [
    "GET" => [
        "/checkout" => [CheckoutController::class, "index"],
        "/pedidos" => [PedidosController::class, "index"],
        "/pedidos/criar" => [PedidosController::class, "create"],
    ],
    "POST" => [
        "/checkout" => [CheckoutController::class, "store"],
        "/pedidos" => [PedidosController::class, "store"],
        "/webhook" => [CheckoutController::class, "webhook"],
    ]
];
