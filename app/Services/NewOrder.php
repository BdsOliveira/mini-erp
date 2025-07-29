<?php

namespace App\Services;

use App\Repositories\BaseRepository;

class NewOrder extends BaseRepository
{
    public function excute(float $total)
    {
        $query = 'INSERT INTO pedidos (user_id, total, status) VALUES (:user_id, :total, "APROVADO")';
        $statement = $this->connection->prepare($query);
        $statement->bindValue('user_id', random_int(1, 999));
        $statement->bindValue('total', $total);
        $statement->execute();

        $pedido = $statement->fetch();

        $query = 'SELECT * FROM pedidos ORDER BY id DESC LIMIT 1';
        $statement = $this->connection->prepare($query);
        $statement->execute();
        $pedido = $statement->fetch();

        (new NewOrderEmail())->excute(total: $total, status: $pedido['status']);

        return $pedido;
    }
}