<?php

declare(strict_types=1);

namespace App\Modules\Checkout\Controllers\Actions;

use App\Modules\Core\Base\BaseController;

class ListOrdersController extends BaseController
{
    public function execute(): void
    {
        $this->render('pedidos/index.php');
    }
}
