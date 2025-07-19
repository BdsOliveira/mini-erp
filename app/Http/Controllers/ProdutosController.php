<?php

declare(strict_types=1);

namespace App\Http\Controllers;

class ProdutosController extends BaseController
{
    public function index()
    {
        return $this->view('produtos/index.php');
    }
    
    public function create()
    {
        return $this->view('products/form.php');
    }

    public function store()
    {
        var_dump("Storing a new product.");
    }
}
