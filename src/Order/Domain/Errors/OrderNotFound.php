<?php

declare(strict_types = 1);

namespace VendingMachine\Order\Domain\Errors;

use VendingMachine\Shared\Domain\Errors\Essentials\EntityNotFound;

final class OrderNotFound extends EntityNotFound
{
    private const string MESSAGE = 'Order not found: %s';

    public static function create(string $orderId): self
    {
        return new self(sprintf(self::MESSAGE, $orderId));
    }
}
