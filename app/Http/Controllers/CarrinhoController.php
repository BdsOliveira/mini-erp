<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Repositories\ProductsRepository;
use App\Services\GetCartData;
use App\Services\GetCupom;
use App\Services\GetFrete;
use Framework\Http\Request;
use Framework\Utils\Session;

class CarrinhoController extends BaseController
{
    public function index()
    {
        $products = (new ProductsRepository())->getCartProducts(ids: Session::get(key: 'cart') ?? []);

        $cartData = GetCartData::excute(products: $products);

        return $this->render('loja/carrinho.php', [
            'products' => $products,
            'flash_message' => Session::getFlash(),
            ...$cartData,
        ]);
    }

    public function addToCart()
    {
        Session::push(key: 'cart', value: (int) Request::get('id'));
        Session::flash(value: 'Produto adicionado ao carrinho.');

        $this->redirect(url: '/');
    }

    public function validateCupom(string $cupom = '')
    {
        $cupom = (new GetCupom())->excute(codigo: Request::get('cupom') ?? $cupom);
        
        Session::set(key: 'cupom', value: $cupom['cupom']);
        Session::set(key: 'desconto', value: $cupom['desconto']);

        Session::flash(value: "{$cupom['cupom']} - {$cupom['message']}");

        $this->redirect(url: '/carrinho');
    }
}
