<?php

declare(strict_types=1);

namespace App\Modules\Catalog\Repositories\Variants;

use App\Modules\Core\Base\BaseRepository;

class UpdateVariantRepository extends BaseRepository
{
    public function execute(int $id, int $productId, string $type, string $value): int|bool
    {
        $query = 'UPDATE variacoes SET tipo = :tipo, valor = :valor WHERE produto_id = :produto_id AND id = :id';
        $statement = $this->connection->prepare($query);
        $statement->bindValue('id', $id);
        $statement->bindValue('produto_id', $productId);
        $statement->bindValue('tipo', $type);
        $statement->bindValue('valor', $value);
        $statement->execute();

        return $id;
    }
}
