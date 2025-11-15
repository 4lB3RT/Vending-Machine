<?php

declare(strict_types = 1);

namespace Tests\Unit\Shared\Domain\Validators;

use VendingMachine\Shared\Domain\Errors\InvalidCollectionType;
use VendingMachine\Shared\Domain\Validators\Collection;

final class TestCollection implements Collection
{
    /* @throws InvalidCollectionType */
    public static function isValid(array $items, string $type): bool
    {
        foreach ($items as $item) {
            if (!$item instanceof $type) {
                throw InvalidCollectionType::create();
            }
        }

        return true;
    }
}
