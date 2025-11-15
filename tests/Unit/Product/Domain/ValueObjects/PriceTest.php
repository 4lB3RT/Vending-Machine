<?php

declare(strict_types = 1);

namespace Tests\Unit\Product\Domain\ValueObjects;

use PHPUnit\Framework\TestCase;
use VendingMachine\Product\Domain\Errors\PriceCannotBeNegative;
use VendingMachine\Product\Domain\ValueObjects\Price;

final class PriceTest extends TestCase
{
    public function testCreatesPriceWithPositiveValue(): void
    {
        $price = Price::fromFloat(10.5);
        $this->assertInstanceOf(Price::class, $price);
        $this->assertEquals(10.5, $price->value());
    }

    public function testThrowsExceptionForNegativePrice(): void
    {
        $this->expectException(PriceCannotBeNegative::class);
        Price::fromFloat(-1.0);
    }
}
