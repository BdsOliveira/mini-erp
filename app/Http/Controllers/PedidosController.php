<?php

declare(strict_types=1);

namespace App\Http\Controllers;

class PedidosController extends BaseController
{
    public function index()
    {
        return $this->render('pedidos/index.php');
    }

    public function create()
    {
        var_dump("Creating a new order.");
    }

    public function store()
    {
        var_dump("Storing a new order.");
    }
}
