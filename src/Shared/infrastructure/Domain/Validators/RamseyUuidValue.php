<?php

declare(strict_types = 1);

namespace VendingMachine\Shared\infrastructure\Domain\Validators;

use Ramsey\Uuid\Uuid;
use VendingMachine\Shared\Domain\Errors\InvalidUuid;
use VendingMachine\Shared\Domain\Validators\UuidValue;

final class RamseyUuidValue implements UuidValue
{
    /* @throws InvalidUuid */
    public static function isValid(string $value): bool
    {
        if (!Uuid::isValid($value)) {
            throw InvalidUuid::create($value);
        }

        return true;
    }
}
