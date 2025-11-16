<?php

declare(strict_types = 1);

namespace VendingMachine\Shared\Domain\Errors;

use VendingMachine\Shared\Domain\Errors\Essentials\BadRequest;

final class InvalidUuid extends BadRequest
{
    private const string MESSAGE = 'Invalid UUID format: %s';

    public static function create(string $uuid): self
    {
        return new self(print_r(sprintf(self::MESSAGE, $uuid), true));
    }
}
