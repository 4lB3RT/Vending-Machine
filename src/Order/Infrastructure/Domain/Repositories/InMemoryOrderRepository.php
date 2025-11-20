<?php

declare(strict_types = 1);

namespace VendingMachine\Order\Infrastructure\Domain\Repositories;

use VendingMachine\Order\Domain\Entities\Order;
use VendingMachine\Order\Domain\Errors\OrderNotFound;
use VendingMachine\Order\Domain\Repositories\OrderRepository;
use VendingMachine\Order\Domain\ValueObjects\OrderId;

class InMemoryOrderRepository implements OrderRepository
{
    /** @var array<string, Order> */
    private array $orders = [];

    public function add(Order $order): void
    {
        $this->orders[$order->id()->value()] = $order;
    }

    /* @throws OrderNotFound */
    public function findById(OrderId $id): Order
    {
        if (!isset($this->orders[$id->value()])) {
            throw OrderNotFound::create($id->value());
        }

        return $this->orders[$id->value()];
    }

    public function save(Order $order): void
    {
        $this->orders[$order->id()->value()] = $order;
    }
}
