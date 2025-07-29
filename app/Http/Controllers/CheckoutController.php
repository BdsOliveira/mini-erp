<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Repositories\ProductsRepository;
use App\Services\GetCartData;
use Framework\Utils\Session;

class CheckoutController extends BaseController
{
    public function index()
    {
        $products = (new ProductsRepository())->getCartProducts(ids: Session::get(key: 'cart') ?? []);

        $cartData = GetCartData::excute(products: $products);

        return $this->render('checkout/index.php', [
            'products' => $products,
            'flash_message' => Session::getFlash(),
            ...$cartData,
        ]);
    }

    public function store()
    {
    }
}
