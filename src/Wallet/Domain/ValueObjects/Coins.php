<?php

declare(strict_types = 1);

namespace VendingMachine\Wallet\Domain\ValueObjects;

use VendingMachine\Shared\Domain\ValueObjects\FloatValue;
use VendingMachine\Wallet\Domain\Errors\CoinsCannotBeNegative;

final readonly class Coins extends FloatValue
{
    /* @throws CoinsCannotBeNegative */
    public static function fromFloat(float $value): self
    {
        if ($value < 0) {
            throw CoinsCannotBeNegative::create($value);
        }

        return new self($value);
    }
}
