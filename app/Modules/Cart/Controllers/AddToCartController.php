<?php

declare(strict_types=1);

namespace App\Modules\Cart\Controllers;

use App\Modules\Core\Base\BaseController;
use Framework\Http\Request;
use Framework\Utils\Session;

class AddToCartController extends BaseController
{
    public function execute(): void
    {
        Session::push(key: 'cart', value: (int) Request::get('id'));
        Session::flash(value: 'Produto adicionado ao carrinho.');

        $this->redirect(url: '/');
    }
}
