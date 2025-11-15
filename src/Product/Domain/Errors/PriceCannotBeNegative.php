<?php

declare(strict_types = 1);

namespace VendingMachine\Product\Domain\Errors;

use VendingMachine\Shared\Domain\Errors\BadRequest;

final class PriceCannotBeNegative extends BadRequest
{
    private const string MESSAGE = 'Price cannot be negative: %s';

    public static function create(float $price): self
    {
        return new self(print_r(sprintf(self::MESSAGE, $price), true));
    }
}
