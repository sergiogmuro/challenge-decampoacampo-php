<?php

namespace App\Services\PriceCalculator\Calculators;

interface PriceCalculatorsInterface
{
    public function calculatePrice(float $value): float;
}
