<?php

declare(strict_types = 1);

namespace Tests\Unit\Wallet\Domain\ValueObjects;

use VendingMachine\Wallet\Domain\ValueObjects\Name;

final class NameMother
{
    public static function create(?string $value = null): Name
    {
        return new Name($value ?? 'TestWallet');
    }
}
