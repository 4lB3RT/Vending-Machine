<?php

declare(strict_types = 1);

namespace Tests\Unit\Product\Domain\Collections;

use PHPUnit\Framework\TestCase;
use Tests\Unit\Product\Domain\Entities\ProductMother;
use Tests\Unit\Shared\Domain\Validators\TestCollection;
use VendingMachine\Product\Domain\Collections\ProductCollection;
use VendingMachine\Shared\Domain\Errors\InvalidCollectionType;

final class ProductCollectionTest extends TestCase
{
    public function testAcceptsOnlyProductInstances(): void
    {
        $validator = new TestCollection();
        $product   = ProductMother::create();

        $products = new ProductCollection($validator, [$product]);
        $this->assertCount(1, $products->items());
        $this->assertSame($product, $products->items()[0]);
    }

    public function testThrowsExceptionForInvalidType(): void
    {
        $validator = new TestCollection();
        $this->expectException(InvalidCollectionType::class);
        new ProductCollection($validator, ['not a product']);
    }
}
