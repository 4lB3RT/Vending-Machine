<?php

declare(strict_types = 1);

namespace VendingMachine\Wallet\Domain\ValueObjects;

use VendingMachine\Shared\Domain\Errors\CoinsCannotBeNegative;
use VendingMachine\Shared\Domain\ValueObjects\Coins;

final readonly class WalletCoins extends Coins
{
    /* @throws CoinsCannotBeNegative */
    public static function fromFloat(float $value): WalletCoins
    {
        return new self($value);
    }
}
