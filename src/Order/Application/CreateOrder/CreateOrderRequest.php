<?php

declare(strict_types = 1);

namespace VendingMachine\Order\Application\CreateOrder;

final readonly class CreateOrderRequest
{
    public function __construct(
        private string $orderId,
        private array  $productIds,
        private string $walletId
    ) {
    }

    public function orderId(): string
    {
        return $this->orderId;
    }

    public function productIds(): array
    {
        return $this->productIds;
    }

    public function walletId(): string
    {
        return $this->walletId;
    }
}
