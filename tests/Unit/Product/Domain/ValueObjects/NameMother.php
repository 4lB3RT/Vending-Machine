<?php

declare(strict_types = 1);

namespace Tests\Unit\Product\Domain\ValueObjects;

use VendingMachine\Product\Domain\ValueObjects\Name;

final class NameMother
{
    public static function create(?string $value = null): Name
    {
        $value = $value ?? self::randomName();

        return Name::fromString($value);
    }

    private static function randomName(): string
    {
        $names = ['Coke', 'Pepsi', 'Sprite', 'Fanta', 'Water', 'Juice'];

        return $names[array_rand($names)];
    }
}
