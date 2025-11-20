<?php

declare(strict_types = 1);

namespace VendingMachine\Order\Domain\Errors;

use VendingMachine\Shared\Domain\Errors\Essentials\BadRequest;

final class OrderCoinsExceeded extends BadRequest
{
    public static function create(float $total, float $coins): self
    {
        return new self("Order total ($total) exceeds wallet coins ($coins)");
    }
}
