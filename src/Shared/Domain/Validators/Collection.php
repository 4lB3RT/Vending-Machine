<?php

declare(strict_types = 1);

namespace VendingMachine\Shared\Domain\Validators;

use VendingMachine\Shared\Domain\Errors\InvalidCollectionType;

interface Collection
{
    /* @throws InvalidCollectionType*/
    public static function isValid(array $items, string $type): bool;
}
