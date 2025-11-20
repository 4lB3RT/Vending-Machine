<?php

declare(strict_types = 1);

namespace VendingMachine\Product\Domain\Entities;

use VendingMachine\Product\Domain\Errors\ProductOutOfStock;
use VendingMachine\Product\Domain\ValueObjects\Name;
use VendingMachine\Product\Domain\ValueObjects\Price;
use VendingMachine\Product\Domain\ValueObjects\ProductId;
use VendingMachine\Product\Domain\ValueObjects\Quantity;

final readonly class Product
{
    public function __construct(
        private ProductId $id,
        private Name      $name,
        private Price     $price,
        private Quantity  $quantity,
    ) {
    }

    public function name(): Name
    {
        return $this->name;
    }

    public function price(): Price
    {
        return $this->price;
    }

    public function quantity(): Quantity
    {
        return $this->quantity;
    }

    public function toArray(): array
    {
        return [
            'id'       => $this->id->value(),
            'name'     => $this->name->value(),
            'price'    => $this->price->value(),
            'quantity' => $this->quantity->value(),
        ];
    }

    /* @throws ProductOutOfStock */
    public function assertStockAvailable(int $requestedQuantity): void
    {
        if ($requestedQuantity > $this->quantity->value()) {
            throw ProductOutOfStock::create(
                $this->id()->value(),
                $requestedQuantity,
                $this->quantity->value()
            );
        }
    }

    public function id(): ProductId
    {
        return $this->id;
    }
}
