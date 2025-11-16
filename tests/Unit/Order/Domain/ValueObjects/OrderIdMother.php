<?php

declare(strict_types = 1);

namespace Tests\Unit\Order\Domain\ValueObjects;

use Tests\Unit\Shared\Domain\Validators\TestUuidValue;
use VendingMachine\Order\ValueObjects\OrderId;

final class OrderIdMother
{
    public static function create(?string $uuid = null): OrderId
    {
        $validator = new TestUuidValue();
        $uuid      = $uuid ?? uuid_create(UUID_TYPE_RANDOM);

        return new OrderId($validator, $uuid);
    }
}
