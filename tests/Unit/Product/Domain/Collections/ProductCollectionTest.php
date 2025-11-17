<?php

declare(strict_types = 1);

namespace Tests\Unit\Product\Domain\Collections;

use PHPUnit\Framework\TestCase;
use Tests\Unit\Product\Domain\Entities\ProductMother;
use VendingMachine\Product\Domain\Collections\ProductCollection;
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
}
