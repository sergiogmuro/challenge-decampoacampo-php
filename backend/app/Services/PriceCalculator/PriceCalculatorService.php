<?php

namespace App\Services\PriceCalculator;

use App\Enums\Currencies;

class PriceCalculatorService
{
    public function calculate(Currencies $currency, float $value): float
    {
        $calculator = PriceCalculatorFactory::make($currency);

        return $calculator->calculatePrice($value);
    }
}
