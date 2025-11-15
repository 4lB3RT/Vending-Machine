<?php

declare(strict_types = 1);

namespace Tests\Unit\Wallet\Domain\ValueObjects;

use PHPUnit\Framework\TestCase;
use VendingMachine\Wallet\Domain\Errors\CoinsCannotBeNegative;
use VendingMachine\Wallet\Domain\ValueObjects\Coins;

final class CoinsTest extends TestCase
{
    public function testCreatesCoinsWithPositiveValue(): void
    {
        $coins = Coins::fromFloat(5.5);
        $this->assertInstanceOf(Coins::class, $coins);
        $this->assertEquals(5.5, $coins->value());
    }

    public function testThrowsExceptionForNegativeCoins(): void
    {
        $this->expectException(CoinsCannotBeNegative::class);
        Coins::fromFloat(-1.0);
    }
}
