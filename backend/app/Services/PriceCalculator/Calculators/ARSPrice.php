<?php

namespace App\Services\PriceCalculator\Calculators;

class ARSPrice implements PriceCalculatorsInterface
{
    public function calculatePrice(float $value): float
    {
        return $value;
    }
}
