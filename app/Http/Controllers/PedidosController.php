<?php

declare(strict_types=1);

namespace App\Http\Controllers;

class PedidosController
{
    public function index()
    {
        var_dump("Displaying all orders.");
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
