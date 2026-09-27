<?php

namespace App\Helpers;

class MoneyFormat
{
    public static function format(float $value, int $decimals = 2): float
    {
        if ($decimals > 0) {
            $value = number_format($value, $decimals, '.', '');
        }

        return $value;
    }
}
