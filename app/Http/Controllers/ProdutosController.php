<?php

declare(strict_types=1);

namespace App\Http\Controllers;

class ProdutosController
{
    public function index()
    {
        var_dump("Displaying all products.");
    }

    public function create()
    {
        var_dump("Creating a new product.");
    }

    public function store()
    {
        var_dump("Storing a new product.");
    }
}
