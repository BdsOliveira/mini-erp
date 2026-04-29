<?php

declare(strict_types=1);

namespace App\Modules\Checkout\Controllers\Actions;

use App\Modules\Core\Base\BaseController;
use App\Modules\Checkout\Services\NewOrder;
use Framework\Utils\Session;

class StoreCheckoutController extends BaseController
{
    public function execute(): void
    {
        (new NewOrder())->execute(total: (float) Session::get(key: 'total'));

        Session::clear();
        Session::flash(value: 'Pedido realizado com sucesso!');

        $this->redirect(url: '/');
    }
}
