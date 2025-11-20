<?php

declare(strict_types = 1);

namespace VendingMachine\Product\Domain\Collections;

use VendingMachine\Product\Domain\Entities\Product;
use VendingMachine\Product\Domain\ValueObjects\ProductId;
use VendingMachine\Shared\Domain\Collections\Collection;
use VendingMachine\Shared\Domain\Errors\CoinsCannotBeNegative;
use VendingMachine\Shared\Domain\ValueObjects\Coins;

final class ProductCollection extends Collection
{
    /**
     * @throws CoinsCannotBeNegative
     */
    public function totalPrice(): Coins
    {
        $totalPrice = 0;
        /** @var Product $product */
        foreach ($this->items() as $product) {
            $totalPrice += $product->price()->value();
        }

        return Coins::fromFloat($totalPrice);
    }

    public function findById(ProductId $productId): ?Product
    {
        return array_find($this->items(), fn (Product $product) => $product->id()->value() === $productId->value());
    }

    protected function type(): string
    {
        return Product::class;
    }
}
