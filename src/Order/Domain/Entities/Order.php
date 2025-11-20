<?php

declare(strict_types = 1);

namespace VendingMachine\Order\Domain\Entities;

use VendingMachine\Order\Domain\ValueObjects\OrderId;
use VendingMachine\Product\Domain\Collections\ProductCollection;
use VendingMachine\Product\Domain\Entities\Product;
use VendingMachine\Wallet\Domain\Entities\Wallet;

final readonly class Order
{
    public function __construct(
        private OrderId $id,
        private ProductCollection $products,
        private Wallet $wallet,
    ) {
    }

    public function id(): OrderId
    {
        return $this->id;
    }

    public function wallet(): Wallet
    {
        return $this->wallet;
    }

    public function toArray(): array
    {
        return [
            'id'       => $this->id->value(),
            'products' => $this->products()->map(fn (Product $product) => $product->toArray()),
            'wallet'   => $this->wallet->toArray(),
        ];
    }

    public function products(): ProductCollection
    {
        return $this->products;
    }
}
