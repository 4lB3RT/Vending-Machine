<?php

declare(strict_types = 1);

namespace VendingMachine\Product\Application\GetProduct;

final readonly class GetProductRequest
{
    public function __construct(private string $productId)
    {
    }

    public function productId(): string
    {
        return $this->productId;
    }
}
