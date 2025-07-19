<?php

declare(strict_types=1);

namespace App\Http\Controllers;

class CuponsController extends BaseController
{
    public function index()
    {
        var_dump("Displaying all cupons.");
    }

    public function create()
    {
        var_dump("Creating a new cupom.");
    }

    public function store()
    {
        var_dump("Storing a new cupom.");
    }
}
