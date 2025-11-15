<?php

declare(strict_types = 1);

namespace Tests\Unit\Product\Domain\ValueObjects;

use Tests\Unit\Shared\Domain\Validators\TestUuidValue;
use VendingMachine\Product\Domain\ValueObjects\ProductId;

final class ProductIdMother
{
    public static function create(?string $uuid = null): ProductId
    {
        $validator = new TestUuidValue();
        $uuid      = $uuid ?? uuid_create(UUID_TYPE_RANDOM);

        return new ProductId($validator, $uuid);
    }
}
