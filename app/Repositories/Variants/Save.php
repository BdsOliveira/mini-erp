<?php

declare(strict_types=1);

namespace App\Repositories\Variants;

use App\Repositories\BaseRepository;

class Save extends BaseRepository
{
    public function execute(int $productId, string $type, string $value): int|bool
    {
        $sanitizedValue = str_replace(search: ' ', replace: '-', subject: $value);

        $sku = $productId . '-' . strtolower($type) . '-' . strtolower($sanitizedValue) . '-' . ($this->getLastInsertId(table: "variacoes") + 1);

        $query = 'INSERT INTO variacoes (produto_id, tipo, valor, sku) VALUES (:produto_id, :tipo, :valor, :sku)';
        $statement = $this->connection->prepare($query);
        $statement->bindValue('produto_id', $productId);
        $statement->bindValue('tipo', $type);
        $statement->bindValue('valor', $value);
        $statement->bindValue('sku', $sku);
        $statement->execute();

        return $this->getLastInsertId(table: "variacoes");
    }
}
