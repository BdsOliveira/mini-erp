<?php

namespace App\Services;

class GetFrete
{
    public static function excute(float $subtotal): float
    {
        if ($subtotal > 200) {
            return 0.00;
        }

        if ($subtotal > 52 && $subtotal <= 166.59) {
            return 15.00;
        }

        return 20.00;
    }
}