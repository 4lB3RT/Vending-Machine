<?php

declare(strict_types = 1);

namespace VendingMachine\Product\Domain\Collections;

use VendingMachine\Product\Domain\ValueObjects\ProductId;
use VendingMachine\Shared\Domain\Collections\Collection;

final class ProductIdCollection extends Collection
{
    protected function type(): string
    {
        return ProductId::class;
    }
}
