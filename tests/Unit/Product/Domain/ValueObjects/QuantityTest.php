<?php

declare(strict_types = 1);

namespace Tests\Unit\Product\Domain\ValueObjects;

use PHPUnit\Framework\TestCase;
use VendingMachine\Product\Domain\Errors\QuantityCannotBeNegative;
use VendingMachine\Product\Domain\ValueObjects\Quantity;

final class QuantityTest extends TestCase
{
    public function testCreatesQuantityWithPositiveValue(): void
    {
        $quantity = Quantity::fromInt(5);
        $this->assertInstanceOf(Quantity::class, $quantity);
        $this->assertEquals(5, $quantity->value());
    }

    public function testThrowsExceptionForNegativeQuantity(): void
    {
        $this->expectException(QuantityCannotBeNegative::class);
        Quantity::fromInt(-1);
    }
}
