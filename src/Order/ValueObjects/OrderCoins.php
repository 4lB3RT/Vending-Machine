<?php

declare(strict_types = 1);

namespace VendingMachine\Order\ValueObjects;

use VendingMachine\Shared\Domain\Errors\CoinsCannotBeNegative;
use VendingMachine\Shared\Domain\ValueObjects\Coins;

final readonly class OrderCoins extends Coins
{
    /* @throws CoinsCannotBeNegative */
    public static function fromFloat(float $value): Coins
    {
        return new self($value);
    }
}
