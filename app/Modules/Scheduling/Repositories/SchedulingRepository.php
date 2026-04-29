<?php

declare(strict_types=1);

namespace App\Modules\Scheduling\Repositories;

use App\Modules\Core\Base\BaseRepository;

class SchedulingRepository extends BaseRepository
{
    public function getAll(): array
    {
        // Exemplo de query SQL pura usando a conexão centralizada
        $query = "SELECT * FROM agendamentos ORDER BY data_agendamento DESC";
        $statement = $this->connection->prepare($query);
        
        // Comentado pois a tabela pode não existir ainda
        // $statement->execute();
        // return $statement->fetchAll();
        
        return []; // Retorno placeholder
    }
}
