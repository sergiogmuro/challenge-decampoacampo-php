<?php

namespace App\Services\PriceCalculator\Calculators;

use Exception;

class USDPrice implements PriceCalculatorsInterface
{
    public function calculatePrice(float $value): float
    {
        $usdPrice = env('PRICE_USD');

        if (!$usdPrice) {
            throw new Exception('USD Price not set');
        }

        return $value / $usdPrice;
    }
}
