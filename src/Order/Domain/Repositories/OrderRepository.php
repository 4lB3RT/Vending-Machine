<?php

declare(strict_types = 1);

namespace VendingMachine\Order\Domain\Repositories;

use VendingMachine\Order\Domain\Entities\Order;
use VendingMachine\Order\ValueObjects\OrderId;

interface OrderRepository
{
    public function save(Order $order): void;

    public function findById(OrderId $id): Order;
}
