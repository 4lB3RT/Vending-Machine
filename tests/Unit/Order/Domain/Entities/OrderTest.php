<?php

declare(strict_types = 1);

namespace Tests\Unit\Order\Domain\Entities;

use PHPUnit\Framework\TestCase;
use Tests\Unit\Order\Domain\ValueObjects\OrderCoinsMother;
use Tests\Unit\Order\Domain\ValueObjects\OrderIdMother;
use Tests\Unit\Product\Domain\Entities\ProductMother;
use Tests\Unit\Wallet\Domain\Entities\WalletMother;
use VendingMachine\Order\Entities\Order;
use VendingMachine\Product\Domain\Collections\ProductCollection;

final class OrderTest extends TestCase
{
    public function testOrderIsCreated(): void
    {
        $productOne = ProductMother::create();
        $productTwo = ProductMother::create();

        $products = new ProductCollection([$productOne, $productTwo]);

        $orderId = OrderIdMother::create();
        $wallet  = WalletMother::create();
        $coins   = OrderCoinsMother::create();

        $order = new Order($orderId, $products, $wallet, $coins);

        $this->assertSame($orderId, $order->id());
        $this->assertSame($products, $order->products());
        $this->assertSame($wallet, $order->wallet());
        $this->assertSame($coins, $order->coins());
    }
}
