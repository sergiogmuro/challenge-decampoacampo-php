<?php

namespace App\Services\PriceCalculator;

use App\Enums\Currencies;
use App\Services\PriceCalculator\Calculators\ARSPrice;
use App\Services\PriceCalculator\Calculators\PriceCalculatorsInterface;
use App\Services\PriceCalculator\Calculators\USDPrice;

class PriceCalculatorFactory
{
    private static $SERVICES = [
        Currencies::ARS->value => ARSPrice::class,
        Currencies::USD->value => USDPrice::class
    ];

    public static function make(Currencies $currency): PriceCalculatorsInterface
    {
        $class = self::$SERVICES[$currency->value];

        return new $class();
    }
}
