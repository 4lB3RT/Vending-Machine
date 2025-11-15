<?php

declare(strict_types = 1);

namespace Tests\Unit\Product\Domain\Entities;

use Tests\Unit\Product\Domain\ValueObjects\NameMother;
use Tests\Unit\Product\Domain\ValueObjects\PriceMother;
use Tests\Unit\Product\Domain\ValueObjects\ProductIdMother;
use Tests\Unit\Product\Domain\ValueObjects\QuantityMother;
use VendingMachine\Product\Domain\Entities\Product;

final class ProductMother
{
    public static function create(
        ?string $uuid = null,
        ?string $name = null,
        ?float $price = null,
        ?int $quantity = null
    ): Product {
        return new Product(
            ProductIdMother::create($uuid),
            NameMother::create($name),
            PriceMother::create($price),
            QuantityMother::create($quantity)
        );
    }
}
