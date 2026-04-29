<?php

declare(strict_types=1);

namespace App\Modules\Checkout\Repositories;

use App\Modules\Checkout\Repositories\Pedidos\Update;

class PedidosRepository
{
    public function updateStatus(int $pedidoId, string $status): int|bool
    {
        return (new Update())->execute(pedidoId: $pedidoId, status: $status);
    }
}
