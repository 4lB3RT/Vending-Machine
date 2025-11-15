<?php

declare(strict_types = 1);

namespace VendingMachine\Product\Domain\Errors;

use VendingMachine\Shared\Domain\Errors\BadRequest;

final class QuantityCannotBeNegative extends BadRequest
{
    private const string MESSAGE = 'Quantity cannot be negative: %s';

    public static function create(float $quantity): self
    {
        return new self(print_r(sprintf(self::MESSAGE, $quantity), true));
    }
}
