<?php

declare(strict_types = 1);

namespace Tests\Unit\Order\Domain\ValueObjects;

use PHPUnit\Framework\TestCase;
use VendingMachine\Order\ValueObjects\OrderCoins;
use VendingMachine\Shared\Domain\Errors\CoinsCannotBeNegative;
use VendingMachine\Shared\Domain\ValueObjects\Coins;

final class OrderCoinsTest extends TestCase
{
    public function testCreatesCoinsWithPositiveValue(): void
    {
        $coins = OrderCoins::fromFloat(5.5);
        $this->assertInstanceOf(Coins::class, $coins);
        $this->assertEquals(5.5, $coins->value());
    }

    public function testThrowsExceptionForNegativeCoins(): void
    {
        $this->expectException(CoinsCannotBeNegative::class);
        OrderCoins::fromFloat(-1.0);
    }
}
