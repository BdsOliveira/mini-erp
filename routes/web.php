<?php

use App\Http\Controllers\CuponsController;
use App\Http\Controllers\ErroController;
use App\Http\Controllers\EstoqueController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PedidosController;
use App\Http\Controllers\ProdutosController;

return [
    "GET" => [
        "" => [HomeController::class, "index"],

        "/pedidos" => [PedidosController::class, "index"],
        "/pedidos/criar" => [PedidosController::class, "create"],

        "/produtos" => [ProdutosController::class, "index"],
        "/produtos/criar" => [ProdutosController::class, "create"],

        "/cupons" => [CuponsController::class, "index"],
        "/cupons/criar" => [CuponsController::class, "create"],

        "/estoque" => [EstoqueController::class, "index"],
        "/estoque/criar" => [EstoqueController::class, "create"],

        "/not-found" => [ErroController::class, "notFound"],
    ],
    "POST" => [
        "/pedidos" => [PedidosController::class, "store"],
        
        "/produtos" => [ProdutosController::class, "store"],

        "/cupons" => [CuponsController::class, "store"],

        "/estoque" => [EstoqueController::class, "store"],
    ],
];
