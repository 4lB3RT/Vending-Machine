<?php

declare(strict_types = 1);

namespace VendingMachine\Product\Domain\Collections;

use VendingMachine\Product\Domain\Entities\Product;
use VendingMachine\Shared\Domain\Collections\Collection;

final class ProductCollection extends Collection
{
    protected function type(): string
    {
        return Product::class;
    }
}
