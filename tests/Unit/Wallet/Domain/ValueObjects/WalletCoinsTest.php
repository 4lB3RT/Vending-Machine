<?php

declare(strict_types = 1);

namespace Tests\Unit\Wallet\Domain\ValueObjects;

use PHPUnit\Framework\TestCase;
use VendingMachine\Shared\Domain\Errors\CoinsCannotBeNegative;
use VendingMachine\Shared\Domain\ValueObjects\Coins;
use VendingMachine\Wallet\Domain\ValueObjects\WalletCoins;

final class WalletCoinsTest extends TestCase
{
    public function testCreatesCoinsWithPositiveValue(): void
    {
        $coins = WalletCoins::fromFloat(5.5);
        $this->assertInstanceOf(Coins::class, $coins);
        $this->assertEquals(5.5, $coins->value());
    }

    public function testThrowsExceptionForNegativeCoins(): void
    {
        $this->expectException(CoinsCannotBeNegative::class);
        WalletCoins::fromFloat(-1.0);
    }
}
