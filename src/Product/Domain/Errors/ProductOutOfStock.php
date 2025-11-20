<?php

declare(strict_types = 1);

namespace VendingMachine\Product\Domain\Errors;

use Exception;

final class ProductOutOfStock extends Exception
{
    public static function create(string $productId, int $requested, int $available): self
    {
        return new self("Product $productId out of stock: requested $requested, available $available");
    }
}
