<?php

declare(strict_types = 1);

namespace VendingMachine\Order\Entities;

use VendingMachine\Order\ValueObjects\OrderCoins;
use VendingMachine\Order\ValueObjects\OrderId;
use VendingMachine\Product\Domain\Collections\ProductCollection;
use VendingMachine\Wallet\Domain\Entities\Wallet;

final readonly class Order
{
    public function __construct(
        private OrderId $id,
        private ProductCollection $products,
        private Wallet $wallet,
        private OrderCoins $coins
    ) {
    }

    public function id(): OrderId
    {
        return $this->id;
    }

    public function products(): ProductCollection
    {
        return $this->products;
    }

    public function wallet(): Wallet
    {
        return $this->wallet;
    }

    public function coins(): OrderCoins
    {
        return $this->coins;
    }
}
