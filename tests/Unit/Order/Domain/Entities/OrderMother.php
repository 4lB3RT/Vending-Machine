<?php

declare(strict_types = 1);

namespace Tests\Unit\Order\Domain\Entities;

use Tests\Unit\Order\Domain\ValueObjects\OrderIdMother;
use Tests\Unit\Product\Domain\Entities\ProductMother;
use Tests\Unit\Wallet\Domain\Entities\WalletMother;
use VendingMachine\Order\Domain\Entities\Order;
use VendingMachine\Order\Domain\ValueObjects\OrderId;
use VendingMachine\Product\Domain\Collections\ProductCollection;
use VendingMachine\Wallet\Domain\Entities\Wallet;

final class OrderMother
{
    public static function create(
        ?OrderId $id = null,
        ?ProductCollection $products = null,
        ?Wallet $wallet = null,
    ): Order {
        $productOne = ProductMother::create();
        $productTwo = ProductMother::create();

        $products = $products ?? new ProductCollection([$productOne, $productTwo]);

        return new Order(
            $id ?? OrderIdMother::create(),
            $products,
            $wallet ?? WalletMother::create(),
        );
    }
}
