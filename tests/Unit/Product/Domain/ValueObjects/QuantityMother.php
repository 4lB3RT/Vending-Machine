<?php

declare(strict_types = 1);

namespace Tests\Unit\Product\Domain\ValueObjects;

use VendingMachine\Product\Domain\ValueObjects\Quantity;

final class QuantityMother
{
    public static function create(?int $value = null): Quantity
    {
        $value = $value ?? mt_rand(1, 100);

        return Quantity::fromInt($value);
    }
}
