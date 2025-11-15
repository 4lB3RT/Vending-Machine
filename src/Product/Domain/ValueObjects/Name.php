<?php

declare(strict_types = 1);

namespace VendingMachine\Product\Domain\ValueObjects;

use VendingMachine\Shared\Domain\ValueObjects\StringValue;

final readonly class Name extends StringValue
{
    public static function fromString(string $value): self
    {
        return new self($value);
    }
}
