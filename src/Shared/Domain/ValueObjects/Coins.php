<?php

declare(strict_types = 1);

namespace VendingMachine\Shared\Domain\ValueObjects;

use VendingMachine\Shared\Domain\Errors\CoinsCannotBeNegative;

abstract readonly class Coins
{
    /* @throws CoinsCannotBeNegative */
    public function __construct(private float $value)
    {
        if ($value < 0) {
            throw CoinsCannotBeNegative::create($value);
        }
    }

    abstract public static function fromFloat(float $value): self;

    public function value(): float
    {
        return $this->value;
    }
}
