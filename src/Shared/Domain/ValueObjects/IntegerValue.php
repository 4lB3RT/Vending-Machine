<?php

declare(strict_types = 1);

namespace VendingMachine\Shared\Domain\ValueObjects;

abstract readonly class IntegerValue
{
    public function __construct(private int $value)
    {
    }

    public function value(): int
    {
        return $this->value;
    }
}
