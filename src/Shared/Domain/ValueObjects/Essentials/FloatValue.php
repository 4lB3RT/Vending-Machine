<?php

declare(strict_types = 1);

namespace VendingMachine\Shared\Domain\ValueObjects\Essentials;

abstract readonly class FloatValue
{
    public function __construct(private float $value)
    {
    }

    public function value(): float
    {
        return $this->value;
    }
}
