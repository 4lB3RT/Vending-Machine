<?php

declare(strict_types = 1);

namespace VendingMachine\Product\Domain\ValueObjects;

use VendingMachine\Product\Domain\Errors\PriceCannotBeNegative;
use VendingMachine\Shared\Domain\ValueObjects\FloatValue;

final readonly class Price extends FloatValue
{
    /* @throws PriceCannotBeNegative */
    public static function fromFloat(float $value): self
    {
        if ($value < 0) {
            throw PriceCannotBeNegative::create($value);
        }

        return new self($value);
    }
}
