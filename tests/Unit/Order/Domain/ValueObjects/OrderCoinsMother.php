<?php

declare(strict_types = 1);

namespace Tests\Unit\Order\Domain\ValueObjects;

use VendingMachine\Order\ValueObjects\OrderCoins;

final class OrderCoinsMother
{
    public static function create(?int $value = null): OrderCoins
    {
        return new OrderCoins($value ?? 100);
    }
}
