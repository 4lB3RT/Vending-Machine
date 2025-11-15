<?php

declare(strict_types = 1);

namespace VendingMachine\Product\Domain\ValueObjects;

use VendingMachine\Product\Domain\Errors\QuantityCannotBeNegative;
use VendingMachine\Shared\Domain\ValueObjects\IntegerValue;

final readonly class Quantity extends IntegerValue
{
    /* @throws QuantityCannotBeNegative */
    public static function fromInt(int $value): self
    {
        if ($value < 0) {
            throw QuantityCannotBeNegative::create($value);
        }

        return new self($value);
    }
}
