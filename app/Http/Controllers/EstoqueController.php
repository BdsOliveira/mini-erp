<?php

declare(strict_types=1);

namespace App\Http\Controllers;

class EstoqueController extends BaseController
{
    public function index()
    {
        var_dump("Displaying all stock items.");
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
