<?php

declare(strict_types = 1);

namespace VendingMachine\Shared\infrastructure\Domain\Validators;

use InvalidArgumentException;
use VendingMachine\Shared\Domain\Errors\InvalidCollectionType;
use VendingMachine\Shared\Domain\Validators\Collection;
use Webmozart\Assert\Assert;

final class WebmozartCollection implements Collection
{
    /* @throws InvalidCollectionType */
    public static function isValid(array $items, string $type): bool
    {
        try {
            Assert::allIsInstanceOf($items, $type);

            return true;
        } catch (InvalidArgumentException) {
            throw InvalidCollectionType::create();
        }
    }
}
