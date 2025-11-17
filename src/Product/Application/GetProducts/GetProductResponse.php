<?php

declare(strict_types = 1);

namespace VendingMachine\Product\Application\GetProducts;

use VendingMachine\Product\Domain\Collections\ProductCollection;

final readonly class GetProductResponse
{
    public function __construct(private ProductCollection $products)
    {
    }

    public function products(): ProductCollection
    {
        return $this->products;
    }
}
