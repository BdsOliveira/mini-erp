<?php

namespace App\Services;

use App\Repositories\BaseRepository;
use Framework\Utils\Mail;

class NewOrderEmail extends BaseRepository
{
    public function excute(float $total, string $status)
    {
        Mail::send(
            to: 'gestao@example.com',
            subject: 'Novo Pedido',
            body: "Ola, um novo pedido foi realizado com sucesso. O total foi de R$ $total e o status do pedido eh $status!",
        );
    }
}