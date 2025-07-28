<?php

namespace App\Services;

use Framework\Utils\Session;

class GetCartData
{
    public static function excute(array $products): array
    {
        $subtotal = array_sum(array_column($products, 'preco'));
        Session::set(key: 'subtotal', value: $subtotal);
        $frete = GetFrete::excute($subtotal);
        $cupom = Session::get(key: 'cupom');
        $cupom_valor = ((float) Session::get(key: 'desconto')) * -1;

        $total = $subtotal + $frete + $cupom_valor;

        return [
            'subtotal' => $subtotal,
            'total_items' => count($products),
            'frete' => $frete,
            'cupom' => $cupom,
            'cupom_valor' => $cupom_valor,
            'total' => $total,
        ];
    }
}