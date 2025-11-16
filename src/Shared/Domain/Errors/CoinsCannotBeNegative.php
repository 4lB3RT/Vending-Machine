<?php

declare(strict_types = 1);

namespace VendingMachine\Shared\Domain\Errors;

use VendingMachine\Shared\Domain\Errors\Essentials\BadRequest;

final class CoinsCannotBeNegative extends BadRequest
{
    private const string MESSAGE = 'Coins cannot be negative: %s';

    public static function create(float $coins): self
    {
        return new self(print_r(sprintf(self::MESSAGE, $coins), true));
    }
}
