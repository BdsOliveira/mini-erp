<?php

use App\Http\Controllers\CarrinhoController;
use App\Http\Controllers\ErroController;
use App\Http\Controllers\LojaController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PedidosController;
use App\Http\Controllers\ProdutosController;

$get_store_routes = [
    '' => [LojaController::class, "index"],
    
    "/carrinho" => [CarrinhoController::class, "index"],
];

$get_admin_routes = [
    "/admin" => [HomeController::class, "index"],

    "/pedidos" => [PedidosController::class, "index"],
    "/pedidos/criar" => [PedidosController::class, "create"],

    "/produtos" => [ProdutosController::class, "index"],
    "/produtos/criar" => [ProdutosController::class, "create"],
    "/produtos/editar" => [ProdutosController::class, "edit"],
    "/produtos/variacoes" => [ProdutosController::class, "variants"],
    "/produtos/variacoes/editar" => [ProdutosController::class, "getProductVariant"],

    "/not-found" => [ErroController::class, "notFound"],
];

$post_admin_routes = [
    "/pedidos" => [PedidosController::class, "store"],

    "/produtos" => [ProdutosController::class, "store"],
    "/produtos/update" => [ProdutosController::class, "update"],
    "/produtos/variacoes/cadastrar" => [ProdutosController::class, "storeVariants"],
    "/produtos/variacoes/update" => [ProdutosController::class, "updateVariant"],
];

$post_store_routes = [
    "/carrinho" => [CarrinhoController::class, "addToCart"],
    "/carrinho/cupom" => [CarrinhoController::class, "validateCupom"],
];

return [
    "GET" => [
        ...$get_admin_routes,
        ...$get_store_routes,
    ],
    "POST" => [
        ...$post_admin_routes,
        ...$post_store_routes,
    ],
];