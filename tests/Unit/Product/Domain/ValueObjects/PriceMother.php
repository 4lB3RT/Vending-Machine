<?php

declare(strict_types = 1);

namespace Tests\Unit\Product\Domain\ValueObjects;

use VendingMachine\Product\Domain\ValueObjects\Price;

final class PriceMother
{
    public static function create(?float $value = null): Price
    {
        $value = $value ?? mt_rand(1, 100) / 10;

        return Price::fromFloat($value);
    }
}
