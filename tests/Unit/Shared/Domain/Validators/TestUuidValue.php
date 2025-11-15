<?php

declare(strict_types = 1);

namespace Tests\Unit\Shared\Domain\Validators;

use VendingMachine\Shared\Domain\Errors\InvalidUuid;
use VendingMachine\Shared\Domain\Validators\UuidValue;

final class TestUuidValue implements UuidValue
{
    /* @throws InvalidUuid */
    public static function isValid(string $value): bool
    {
        if (preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/i', $value) !== 1) {
            throw InvalidUuid::create($value);
        }

        return true;
    }
}
