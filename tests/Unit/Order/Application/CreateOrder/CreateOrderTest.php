<?php

declare(strict_types = 1);

namespace Tests\Unit\Order\Application\CreateOrder;

use PHPUnit\Framework\TestCase;
use Tests\Unit\Product\Domain\Entities\ProductMother;
use Tests\Unit\Product\Domain\ValueObjects\ProductIdMother;
use Tests\Unit\Shared\Domain\Validators\TestUuidValue;
use VendingMachine\Order\Application\CreateOrder\CreateOrder;
use VendingMachine\Order\Application\CreateOrder\CreateOrderRequest;
use VendingMachine\Order\Domain\Entities\Order;
use VendingMachine\Order\Infrastructure\Domain\Repositories\InMemoryOrderRepository;
use VendingMachine\Order\ValueObjects\OrderId;
use VendingMachine\Product\Domain\Errors\ProductOutOfStock;
use VendingMachine\Product\Domain\Errors\ProductsNotFound;
use VendingMachine\Product\Infrastructure\Domain\Repositories\InMemoryProductRepository;
use VendingMachine\Wallet\Domain\Entities\Wallet;
use VendingMachine\Wallet\Domain\Errors\NotEnoughCoins;
use VendingMachine\Wallet\Domain\Errors\WalletNotFound;
use VendingMachine\Wallet\Domain\ValueObjects\Name;
use VendingMachine\Wallet\Domain\ValueObjects\WalletCoins;
use VendingMachine\Wallet\Domain\ValueObjects\WalletId;
use VendingMachine\Wallet\Infrastructure\Domain\Repositories\InMemoryWalletRepository;

final class CreateOrderTest extends TestCase
{
    private InMemoryOrderRepository $orderRepository;
    private InMemoryProductRepository $productRepository;
    private InMemoryWalletRepository $walletRepository;
    private CreateOrder $sut;

    public function testCreateOrderSuccessfully(): void
    {
        $orderId   = '123e4567-e89b-12d3-a456-426614174999';
        $productId = ProductIdMother::create('123e4567-e89b-12d3-a456-426614174777')->value();
        $product   = ProductMother::create($productId, 'TestProduct', 10, 10);
        $this->productRepository->add($product);
        $walletId = '123e4567-e89b-12d3-a456-426614174888';
        $wallet   = new Wallet(new WalletId(new TestUuidValue(), $walletId), Name::fromString('TestWallet'), WalletCoins::fromFloat(100));
        $this->walletRepository->save($wallet);
        $request = new CreateOrderRequest(
            $orderId,
            [$productId],
            $walletId,
        );

        $this->sut->execute($request);
        $created = $this->orderRepository->findById(new OrderId(new TestUuidValue(), $orderId));

        $this->assertInstanceOf(Order::class, $created);
        $this->assertEquals($orderId, $created->id()->value());
        $this->assertEquals($walletId, $created->wallet()->id()->value());
        $this->assertCount(1, $created->products()->items());
        $this->assertEquals('TestProduct', $created->products()->items()[0]->name()->value());
    }

    public function testCreateOrderFailsIfNotEnoughCoins(): void
    {
        $orderId   = '123e4567-e89b-12d3-a456-426614174101';
        $productId = ProductIdMother::create('123e4567-e89b-12d3-a456-426614174001')->value();
        $product   = ProductMother::create($productId, 'ExpensiveProduct', 100, 1);

        $this->productRepository->add($product);

        $walletId = '123e4567-e89b-12d3-a456-426614174011';
        $wallet   = new Wallet(new WalletId(new TestUuidValue(), $walletId), Name::fromString('TestWallet'), WalletCoins::fromFloat(50));
        $this->walletRepository->save($wallet);

        $request = new CreateOrderRequest($orderId, [$productId], $walletId);

        $this->expectException(NotEnoughCoins::class);
        $this->sut->execute($request);
    }

    public function testCreateOrderFailsIfProductOutOfStock(): void
    {
        $orderId   = '123e4567-e89b-12d3-a456-426614174102';
        $productId = ProductIdMother::create('123e4567-e89b-12d3-a456-426614174002')->value();
        $product   = ProductMother::create($productId, 'LimitedProduct', 10, 1);

        $this->productRepository->save($product);

        $walletId = '123e4567-e89b-12d3-a456-426614174012';
        $wallet   = new Wallet(new WalletId(new TestUuidValue(), $walletId), Name::fromString('TestWallet'), WalletCoins::fromFloat(100));
        $this->walletRepository->save($wallet);

        $request = new CreateOrderRequest($orderId, [$productId, $productId], $walletId);

        $this->expectException(ProductOutOfStock::class);

        $this->sut->execute($request);
    }

    public function testCreateOrderFailsIfWalletNotFound(): void
    {
        $orderId   = '123e4567-e89b-12d3-a456-426614174103';
        $productId = ProductIdMother::create('123e4567-e89b-12d3-a456-426614174003')->value();
        $product   = ProductMother::create($productId, 'AnyProduct', 10, 10);

        $this->productRepository->add($product);

        $walletId = '123e4567-e89b-12d3-a456-426614179999';
        $request  = new CreateOrderRequest($orderId, [$productId], $walletId);

        $this->expectException(WalletNotFound::class);

        $this->sut->execute($request);
    }

    public function testCreateOrderFailsIfProductsNotFound(): void
    {
        $orderId   = '123e4567-e89b-12d3-a456-426614174104';
        $productId = ProductIdMother::create('123e4567-e89b-12d3-a456-426614174004')->value();
        $walletId  = '123e4567-e89b-12d3-a456-426614174012';
        $wallet    = new Wallet(new WalletId(new TestUuidValue(), $walletId), Name::fromString('TestWallet'), WalletCoins::fromFloat(100));

        $this->walletRepository->save($wallet);

        $request = new CreateOrderRequest($orderId, [$productId], $walletId);
        $this->expectException(ProductsNotFound::class);

        $this->sut->execute($request);
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->orderRepository   = new InMemoryOrderRepository();
        $this->productRepository = new InMemoryProductRepository();
        $this->walletRepository  = new InMemoryWalletRepository();
        $this->sut               = new CreateOrder(
            new TestUuidValue(),
            $this->orderRepository,
            $this->productRepository,
            $this->walletRepository
        );
    }
}
