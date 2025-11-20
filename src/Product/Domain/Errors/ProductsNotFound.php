<?php

declare(strict_types = 1);

namespace VendingMachine\Product\Domain\Errors;

use VendingMachine\Shared\Domain\Errors\Essentials\EntityNotFound;

final class ProductsNotFound extends EntityNotFound
{
    private const string MESSAGE = 'Products not found: %s';

    public static function create(array $productIds): self
    {
        return new self(sprintf(self::MESSAGE, implode(', ', $productIds)));
    }
}
