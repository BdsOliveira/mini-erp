<?php

declare(strict_types=1);

namespace App\Modules\Core\Controllers;

use App\Modules\Core\Base\BaseController;

class ErroController extends BaseController
{
    public function notFound(): void
    {
        $this->render('errors/not-found.php');
    }
}