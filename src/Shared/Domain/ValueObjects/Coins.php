<?php

declare(strict_types = 1);

namespace VendingMachine\Shared\Domain\ValueObjects;

use VendingMachine\Shared\Domain\Errors\CoinsCannotBeNegative;

readonly class Coins
{
    /* @throws CoinsCannotBeNegative */
    public function __construct(private float $value)
    {
        if ($value < 0) {
            throw CoinsCannotBeNegative::create($value);
        }
    }

    /* @throws CoinsCannotBeNegative */
    public static function fromFloat(float $value): self
    {
        return new self($value);
    }

    public function value(): float
    {
        return $this->value;
    }
}
