<?php

declare(strict_types = 1);

namespace VendingMachine\Shared\Domain\Validators;

use VendingMachine\Shared\Domain\Errors\InvalidUuid;

interface UuidValue
{
    /** @throws InvalidUuid */
    public static function isValid(string $value): bool;
}
