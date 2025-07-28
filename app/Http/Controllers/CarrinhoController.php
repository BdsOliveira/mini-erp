<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Repositories\ProductsRepository;
use App\Services\GetFrete;
use Framework\Http\Request;
use Framework\Utils\Session;

class CarrinhoController extends BaseController
{
    public function index()
    {
        $products = (new ProductsRepository())->getCartProducts(ids: Session::get(key: 'cart') ?? []);

        $subtotal = array_sum(array_column($products, 'preco'));
        $frete = GetFrete::excute($subtotal);
        $cupom = 0;

        return $this->render('loja/carrinho.php', [
            'products' => $products,
            'flash_message' => Session::getFlash(),
            'subtotal' => $subtotal,
            'total_items' => count($products),
            'frete' => $frete,
            'total' => array_sum(array_column($products, 'preco')) + $frete + $cupom,
        ]);
    }

    public function addToCart()
    {
        Session::push(key: 'cart', value: (int) Request::get('id'));
        Session::flash(value: 'Produto adicionado ao carrinho.');

        $this->redirect(url: '/');
    }
}
