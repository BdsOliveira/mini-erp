<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Repositories\PedidosRepository;
use App\Repositories\ProductsRepository;
use App\Services\GetCartData;
use App\Services\NewOrder;
use Framework\Http\Request;
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
        (new NewOrder())->excute(total: (float) Session::get(key: 'total'));

        Session::clear();
        Session::flash(value: 'Pedido realizado com sucesso!');

        $this->redirect(url: '/');
    }

    public function webhook()
    {
        $id = (int) Request::get('id');
        $status = Request::get('status');

        (new PedidosRepository())->updateStatus(pedidoId: $id, status: $status);

        header('Content-Type: application/json; charset=utf-8');

        $response_data = [
            'success' => true,
            'message' => 'Produto atualizado.',
        ];

        $json_response = json_encode($response_data);

        echo $json_response;
    }
}
