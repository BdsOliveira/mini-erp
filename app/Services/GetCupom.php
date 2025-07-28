<?php

namespace App\Services;

use App\Repositories\BaseRepository;
use DateTime;
use Framework\Utils\Session;

class GetCupom extends BaseRepository
{
    public function excute(string $codigo): array
    {
        $codigo = strtoupper($codigo);
        $query = 'SELECT * FROM cupons WHERE codigo = :codigo AND ativo = 1 LIMIT 1';
        $statement = $this->connection->prepare($query);
        $statement->bindValue('codigo', $codigo);
        $statement->execute();

        $cupom = $statement->fetch();

        if (!$cupom) {
            return [
                'cupom' => $codigo,
                'message' => 'Cupom não encontrado.',
                'desconto' => 0,
            ];
        }

        $date = new DateTime($cupom['validade']);
        if ($date < new DateTime()) {
            return [
                'cupom' => $codigo,
                'message' => 'Cupom expirado.',
                'desconto' => 0,
            ];
        }

        if ($cupom['quantidade'] <= 0) {
            return [
                'cupom' => $codigo,
                'message' => 'Cupom esgotado.',
                'desconto' => 0,
            ];
        }

        if (Session::get('subtotal') < $cupom['valor_minimo']) {
            return [
                'cupom' => $codigo,
                'message' => 'Cupom não atende o valor mínimo.',
                'desconto' => 0,
            ];
        }

        return [
            'cupom' => $codigo,
            'message' => 'Cupom adicionado ao carrinho.',
            'desconto' => $cupom['desconto'],
        ];
    }
}