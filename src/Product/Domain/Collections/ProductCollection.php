<?php

declare(strict_types = 1);

namespace VendingMachine\Product\Domain\Collections;

use VendingMachine\Product\Domain\Entities\Product;
use VendingMachine\Shared\Domain\Collections\Collection;
use VendingMachine\Shared\Domain\Errors\CoinsCannotBeNegative;
use VendingMachine\Shared\Domain\ValueObjects\Coins;

final class ProductCollection extends Collection
{
    /**
     * @throws CoinsCannotBeNegative
     */
    public function totalPrice(array $productIdsQuantity): Coins
    {
        $totalPrice = 0;
        /** @var Product $product */
        foreach ($this->items() as $product) {
            $totalPrice += $product->price()->value() * $productIdsQuantity[$product->id()->value()];
        }

        return Coins::fromFloat($totalPrice);
    }

    protected function type(): string
    {
        return Product::class;
    }
}
