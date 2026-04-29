<?php

declare(strict_types=1);

namespace App\Modules\Checkout\Controllers\Actions;

use App\Modules\Core\Base\BaseController;
use App\Modules\Checkout\Repositories\Pedidos\UpdateStatusRepository;
use Framework\Http\Request;

class WebhookCheckoutController extends BaseController
{
    public function execute(): void
    {
        $id = (int) Request::get('id');
        $status = Request::get('status');

        (new UpdateStatusRepository())->execute(pedidoId: $id, status: $status);

        header('Content-Type: application/json; charset=utf-8');

        echo json_encode([
            'success' => true,
            'message' => 'Produto atualizado.',
        ]);
    }
}
