<?php

declare(strict_types=1);

namespace App\Modules\Cart\Controllers;

use App\Modules\Core\Base\BaseController;
use App\Modules\Catalog\Repositories\Products\GetCartProductsRepository;
use App\Modules\Cart\Services\GetCartData;
use Framework\Utils\Session;

class ListCartController extends BaseController
{
    public function execute(): void
    {
        $products = (new GetCartProductsRepository())->execute(ids: Session::get(key: 'cart') ?? []);
        $cartData = GetCartData::execute(products: $products);

        $this->render('loja/carrinho.php', [
            'products' => $products,
            'flash_message' => Session::getFlash(),
            ...$cartData,
        ]);
    }
}
