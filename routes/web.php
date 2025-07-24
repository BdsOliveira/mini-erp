<?php

use App\Http\Controllers\CuponsController;
use App\Http\Controllers\ErroController;
use App\Http\Controllers\EstoqueController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PedidosController;
use App\Http\Controllers\ProdutosController;

$get_store_routes = [

];

$get_admin_routes = [
    "/admin" => [HomeController::class, "index"],

    "/pedidos" => [PedidosController::class, "index"],
    "/pedidos/criar" => [PedidosController::class, "create"],

    "/produtos" => [ProdutosController::class, "index"],
    "/produtos/criar" => [ProdutosController::class, "create"],
    "/produtos/variacoes" => [ProdutosController::class, "variants"],
    
    "/cupons" => [CuponsController::class, "index"],
    "/cupons/criar" => [CuponsController::class, "create"],
    
    "/estoque" => [EstoqueController::class, "index"],
    "/estoque/criar" => [EstoqueController::class, "create"],
    
    "/not-found" => [ErroController::class, "notFound"],
];

return [
    "GET" => [
        ...$get_admin_routes,
        ...$get_store_routes
    ],
    "POST" => [
        "/pedidos" => [PedidosController::class, "store"],
        
        "/produtos" => [ProdutosController::class, "store"],
        "/produtos/variacoes/cadastrar" => [ProdutosController::class, "storeVariants"],

        "/cupons" => [CuponsController::class, "store"],

        "/estoque" => [EstoqueController::class, "store"],
    ],
];