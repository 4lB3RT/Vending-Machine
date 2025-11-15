<?php

declare(strict_types = 1);

namespace Tests\Unit\Wallet\Domain\ValueObjects;

use VendingMachine\Wallet\Domain\ValueObjects\Coins;

final class CoinsMother
{
    public static function create(?float $value = null): Coins
    {
        $value = $value ?? mt_rand(1, 100) / 10;

        return Coins::fromFloat($value);
    }
}
