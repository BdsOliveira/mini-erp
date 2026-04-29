<?php

declare(strict_types=1);

namespace App\Modules\Cart\Controllers;

use App\Modules\Core\Base\BaseController;
use App\Modules\Support\Services\GetCupom;
use Framework\Http\Request;
use Framework\Utils\Session;

class ValidateCupomController extends BaseController
{
    public function execute(): void
    {
        $cupom = (new GetCupom())->execute(codigo: Request::get('cupom'));
        
        Session::set(key: 'cupom', value: $cupom['cupom']);
        Session::set(key: 'desconto', value: $cupom['desconto']);

        Session::flash(value: "{$cupom['cupom']} - {$cupom['message']}");

        $this->redirect(url: '/carrinho');
    }
}
