<?php

declare(strict_types=1);

namespace App\Http\Controllers;

class EstoqueController extends BaseController
{
    public function index()
    {
        return $this->view('estoque/index.php');
    }

    public function create()
    {
        var_dump("Creating a new stock item.");
    }

    public function store()
    {
        var_dump("Storing a new stock item.");
    }
}
