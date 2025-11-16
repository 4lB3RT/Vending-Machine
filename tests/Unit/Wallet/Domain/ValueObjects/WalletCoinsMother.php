<?php

declare(strict_types = 1);

namespace Tests\Unit\Wallet\Domain\ValueObjects;

use VendingMachine\Wallet\Domain\ValueObjects\WalletCoins;

final class WalletCoinsMother
{
    public static function create(?float $value = null): WalletCoins
    {
        $value = $value ?? mt_rand(1, 100) / 10;

        return WalletCoins::fromFloat($value);
    }
}
