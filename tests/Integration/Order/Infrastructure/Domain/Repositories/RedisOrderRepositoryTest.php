<?php

declare(strict_types = 1);

namespace Tests\Integration\Order\Infrastructure\Domain\Repositories;

use Illuminate\Redis\Connections\Connection;
use Illuminate\Redis\RedisManager;
use Tests\TestCase;
use Tests\Unit\Product\Domain\Entities\ProductMother;
use Tests\Unit\Shared\Domain\Validators\TestUuidValue;
use VendingMachine\Order\Domain\Entities\Order;
use VendingMachine\Order\Domain\Errors\OrderNotFound;
use VendingMachine\Order\Domain\ValueObjects\OrderId;
use VendingMachine\Order\Infrastructure\Domain\Repositories\RedisOrderRepository;
use VendingMachine\Product\Domain\Collections\ProductCollection;
use VendingMachine\Wallet\Domain\Entities\Wallet;
use VendingMachine\Wallet\Domain\ValueObjects\Name;
use VendingMachine\Wallet\Domain\ValueObjects\WalletCoins;
use VendingMachine\Wallet\Domain\ValueObjects\WalletId;

final class RedisOrderRepositoryTest extends TestCase
{
    /** @var Connection */
    private $redis;

    /** @var RedisOrderRepository */
    private $sut;

    public function testSaveStoresOrderCorrectly(): void
    {
        $order = $this->makeOrder();

        $this->sut->save($order);

        $data       = $this->redis->command('get', ['order:' . $order->id()->value()]);
        $orderArray = json_decode($data, true);

        $this->assertEquals($order->id()->value(), $orderArray['id']);
        $this->assertEquals(20, $orderArray['wallet']['coins']);
        $this->assertCount(2, $orderArray['products']);
        $products = $order->products()->items();
        foreach ($products as $i => $product) {
            $this->assertEquals($product->id()->value(), $orderArray['products'][$i]['id']);
            $this->assertEquals($product->name()->value(), $orderArray['products'][$i]['name']);
            $this->assertEquals($product->price()->value(), $orderArray['products'][$i]['price']);
            $this->assertEquals($product->quantity()->value(), $orderArray['products'][$i]['quantity']);
        }
    }

    private function makeOrder(): Order
    {
        $uuidValidator = new TestUuidValue();
        $orderId       = new OrderId($uuidValidator, '123e4567-e89b-12d3-a456-426614174999');
        $walletId      = new WalletId($uuidValidator, '123e4567-e89b-12d3-a456-426614174888');
        $wallet        = new Wallet($walletId, Name::fromString('test wallet'), WalletCoins::fromFloat(20));
        $productOne    = ProductMother::create();
        $productTwo    = ProductMother::create();

        $products = new ProductCollection([$productOne, $productTwo]);

        return new Order($orderId, $products, $wallet);
    }

    public function testFindByIdReturnsCorrectOrder(): void
    {
        $order = $this->makeOrder();
        $this->sut->save($order);
        $foundOrder = $this->sut->findById($order->id());

        $this->assertEquals($order->id()->value(), $foundOrder->id()->value());
        $this->assertEquals($order->wallet()->id()->value(), $foundOrder->wallet()->id()->value());
        $this->assertEquals($order->wallet()->name()->value(), $foundOrder->wallet()->name()->value());
        $this->assertEquals($order->wallet()->coins()->value(), $foundOrder->wallet()->coins()->value());
        $originalProducts = $order->products()->items();
        $foundProducts    = $foundOrder->products()->items();
        $this->assertCount(count($originalProducts), $foundProducts);
        foreach ($originalProducts as $i => $product) {
            $this->assertEquals($product->id()->value(), $foundProducts[$i]->id()->value());
            $this->assertEquals($product->name()->value(), $foundProducts[$i]->name()->value());
            $this->assertEquals($product->price()->value(), $foundProducts[$i]->price()->value());
            $this->assertEquals($product->quantity()->value(), $foundProducts[$i]->quantity()->value());
        }
    }

    public function testFindByIdThrowsOrderNotFound(): void
    {
        $this->expectException(OrderNotFound::class);
        $invalidId = new OrderId(new TestUuidValue(), '123e4567-e89b-12d3-a456-426614179999');
        $this->sut->findById($invalidId);
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->redis = app(RedisManager::class)->connection();
        $this->redis->command('flushdb');
        $uuidValidator = new TestUuidValue();
        $this->sut     = new RedisOrderRepository($this->redis, $uuidValidator);
    }
}
