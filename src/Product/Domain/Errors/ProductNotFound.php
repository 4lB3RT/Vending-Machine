<?php

declare(strict_types = 1);

namespace VendingMachine\Product\Domain\Errors;

use VendingMachine\Shared\Domain\Errors\Essentials\EntityNotFound;

final class ProductNotFound extends EntityNotFound
{
    private const string MESSAGE = 'Product not found: %s';

    public static function create(string $productId): self
    {
        return new self(sprintf(self::MESSAGE, $productId));
    }
}
