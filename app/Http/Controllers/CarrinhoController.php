<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Framework\Http\Request;
use Framework\Utils\Session;

class CarrinhoController extends BaseController
{
    public function addToCart()
    {
        Session::push(key: 'cart', value: (int) Request::get('id'));
        Session::flash(value: 'Produto adicionado ao carrinho.');

        $this->redirect(url: '/');
    }
}
