<?php

declare(strict_types = 1);

namespace Tests\Unit\Wallet\Domain\ValueObjects;

use Tests\Unit\Shared\Domain\Validators\TestUuidValue;
use VendingMachine\Wallet\Domain\ValueObjects\WalletId;

final class WalletIdMother
{
    public static function create(?string $uuid = null): WalletId
    {
        $validator = new TestUuidValue();
        $uuid      = $uuid ?? uuid_create(UUID_TYPE_RANDOM);

        return new WalletId($validator, $uuid);
    }
}
