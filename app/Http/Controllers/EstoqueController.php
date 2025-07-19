<?php

namespace App\Http\Controllers;

class EstoqueController
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
