<?php

declare(strict_types = 1);

namespace VendingMachine\Product\Domain\ValueObjects;

use VendingMachine\Shared\Domain\ValueObjects\FloatValue;

final readonly class Price extends FloatValue
{
    public static function fromFloat(float $value): self
    {
        return new self($value);
    }
}
