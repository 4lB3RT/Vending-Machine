<?php

declare(strict_types = 1);

namespace VendingMachine\Product\Application\GetProduct;

use VendingMachine\Product\Domain\Entities\Product;

final readonly class GetProductResponse
{
    public function __construct(private Product $product)
    {
    }

    public function product(): Product
    {
        return $this->product;
    }
}
