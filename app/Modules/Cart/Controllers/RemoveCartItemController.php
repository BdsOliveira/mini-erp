<?php

declare(strict_types=1);

namespace App\Modules\Cart\Controllers;

use App\Modules\Core\Base\BaseController;
use Framework\Http\Request;
use Framework\Utils\Session;

class RemoveCartItemController extends BaseController
{
    public function execute(): void
    {
        Session::remove(key: 'cart', index: (int) Request::get('productId'));
        $this->redirect(url: '/carrinho');
    }
}
