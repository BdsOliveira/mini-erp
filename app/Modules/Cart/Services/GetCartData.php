<?php

namespace App\Modules\Cart\Services;

use Framework\Utils\Session;
use App\Modules\Support\Services\GetFrete;

class GetCartData
{
    public static function execute(array $products): array
    {
        $subtotal = array_sum(array_column($products, 'preco'));
        Session::set(key: 'subtotal', value: $subtotal);
        $frete = GetFrete::execute($subtotal);
        $cupom = Session::get(key: 'cupom');
        $cupom_valor = ((float) Session::get(key: 'desconto')) * -1;
        
        $total = $subtotal + $frete + $cupom_valor;
        Session::set(key: 'total', value: $total);

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