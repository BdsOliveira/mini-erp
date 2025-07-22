<?php

declare(strict_types=1);

namespace App\Http\Controllers;

class ErroController extends BaseController
{
    public function notFound()
    {
        return $this->render('errors/not-found.php');
    }
}