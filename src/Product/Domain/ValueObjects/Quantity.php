<?php

declare(strict_types = 1);

namespace VendingMachine\Product\Domain\ValueObjects;

use VendingMachine\Shared\Domain\ValueObjects\IntegerValue;

final readonly class Quantity extends IntegerValue
{
    public static function fromInt(int $value): self
    {
        return new self($value);
    }
}
