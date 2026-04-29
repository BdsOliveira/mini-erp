<?php

use App\Modules\Checkout\Controllers\Actions\ListCheckoutController;
use App\Modules\Checkout\Controllers\Actions\StoreCheckoutController;
use App\Modules\Checkout\Controllers\Actions\WebhookCheckoutController;
use App\Modules\Checkout\Controllers\Actions\ListOrdersController;

return [
    "GET" => [
        "/checkout" => [ListCheckoutController::class, "execute"],
        "/pedidos" => [ListOrdersController::class, "execute"],
    ],
    "POST" => [
        "/checkout" => [StoreCheckoutController::class, "execute"],
        "/webhook" => [WebhookCheckoutController::class, "execute"],
    ]
];
