<?php

declare(strict_types=1);

namespace App\Http\Controllers;

class HomeController extends BaseController
{
    public function index()
    {
        return $this->view(
            'index.php',
        );
    }
}