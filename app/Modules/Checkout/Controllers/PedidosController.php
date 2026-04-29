<?php

declare(strict_types=1);

namespace App\Modules\Checkout\Controllers;

use App\Modules\Core\Base\BaseController;

class PedidosController extends BaseController
{
    public function index()
    {
        return $this->render('pedidos/index.php');
    }
}
