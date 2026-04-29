<?php

declare(strict_types=1);

namespace App\Modules\Checkout\Repositories\Pedidos;

use App\Modules\Core\Base\BaseRepository;
use Exception;
use PDOException;

class UpdateStatusRepository extends BaseRepository
{
    public function execute(int $pedidoId, string $status): int|bool
    {
        try {
            $sql = "UPDATE pedidos SET status = :status WHERE id = :id";
            $statement = $this->connection->prepare($sql);
            $statement->bindValue(':id', $pedidoId);
            $statement->bindValue(':status', $status);
            $statement->execute();

            return $this->getLastInsertId(table: "pedidos");
        } catch (PDOException $e) {
            throw new Exception("Erro ao atualizar status do pedido: " . $e->getMessage());
        }
    }
}
