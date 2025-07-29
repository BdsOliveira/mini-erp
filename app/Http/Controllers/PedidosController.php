<?php

declare(strict_types=1);

namespace App\Http\Controllers;

class PedidosController extends BaseController
{
    public function index()
    {
        return $this->render('pedidos/index.php');
    }
}
