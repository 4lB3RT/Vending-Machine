<?php

declare(strict_types = 1);

namespace Tests\Unit\Product\Domain\Collections;

use PHPUnit\Framework\TestCase;
use Tests\Unit\Product\Domain\Entities\ProductMother;
use VendingMachine\Product\Domain\Collections\ProductCollection;
use VendingMachine\Shared\Domain\Errors\CoinsCannotBeNegative;
use VendingMachine\Shared\Domain\Errors\InvalidCollectionType;

final class ProductCollectionTest extends TestCase
{
    public function testAcceptsOnlyProductInstances(): void
    {
        $product = ProductMother::create();

        $products = new ProductCollection([$product]);
        $this->assertCount(1, $products->items());
        $this->assertSame($product, $products->items()[0]);
    }

    public function testThrowsExceptionForInvalidType(): void
    {
        $this->expectException(InvalidCollectionType::class);
        $products = new ProductCollection(['not a product']);
        dd($products);
    }

    public function testTotalPriceCalculatesCorrectly(): void
    {
        $product1   = ProductMother::create('123e4567-e89b-12d3-a456-426614174001', 'Coke', 2.0, 10);
        $product2   = ProductMother::create('123e4567-e89b-12d3-a456-426614174002', 'Pepsi', 3.0, 5);
        $collection = new ProductCollection([$product1, $product2]);
        $quantities = [
            '123e4567-e89b-12d3-a456-426614174001' => 2,
            '123e4567-e89b-12d3-a456-426614174002' => 3,
        ];
        $total = $collection->totalPrice($quantities);
        $this->assertEquals(2.0 * 2 + 3.0 * 3, $total->value());
    }

    public function testTotalPriceReturnsZeroForEmptyCollection(): void
    {
        $collection = new ProductCollection([]);
        $quantities = [];
        $total      = $collection->totalPrice($quantities);
        $this->assertEquals(0, $total->value());
    }

    public function testTotalPriceThrowsIfNegativeQuantity(): void
    {
        $product    = ProductMother::create('123e4567-e89b-12d3-a456-426614174001', 'Coke', 2.0, 10);
        $collection = new ProductCollection([$product]);
        $quantities = ['123e4567-e89b-12d3-a456-426614174001' => -1];
        $this->expectException(CoinsCannotBeNegative::class);
        $collection->totalPrice($quantities);
    }
}
