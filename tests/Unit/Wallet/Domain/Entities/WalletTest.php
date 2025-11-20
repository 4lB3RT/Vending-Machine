<?php

declare(strict_types = 1);

namespace Tests\Unit\Wallet\Domain\Entities;

use PHPUnit\Framework\TestCase;
use Tests\Unit\Shared\Domain\Validators\TestUuidValue;
use Tests\Unit\Wallet\Domain\ValueObjects\NameMother;
use Tests\Unit\Wallet\Domain\ValueObjects\WalletCoinsMother;
use Tests\Unit\Wallet\Domain\ValueObjects\WalletIdMother;
use VendingMachine\Product\Domain\Collections\ProductCollection;
use VendingMachine\Product\Domain\Entities\Product;
use VendingMachine\Product\Domain\ValueObjects\Name;
use VendingMachine\Product\Domain\ValueObjects\Price;
use VendingMachine\Product\Domain\ValueObjects\ProductId;
use VendingMachine\Product\Domain\ValueObjects\Quantity;
use VendingMachine\Shared\Domain\ValueObjects\Coins;
use VendingMachine\Wallet\Domain\Entities\Wallet;
use VendingMachine\Wallet\Domain\Errors\NotEnoughCoins;

final class WalletTest extends TestCase
{
    public function testCreatesWalletWithValidValues(): void
    {
        $walletId = WalletIdMother::create();
        $name     = NameMother::create();
        $coins    = WalletCoinsMother::create();

        $wallet = new Wallet($walletId, $name, $coins);

        $this->assertSame($walletId, $wallet->id());
        $this->assertSame($name, $wallet->name());
        $this->assertSame($coins, $wallet->coins());
    }

    public function testToArrayReturnsCorrectValues(): void
    {
        $walletId = WalletIdMother::create('123e4567-e89b-12d3-a456-426614174000');
        $name     = NameMother::create('TestWallet');
        $coins    = WalletCoinsMother::create(10);
        $wallet   = new Wallet($walletId, $name, $coins);

        $expected = [
            'id'    => '123e4567-e89b-12d3-a456-426614174000',
            'name'  => 'TestWallet',
            'coins' => 10,
        ];

        $this->assertEquals($expected, $wallet->toArray());
    }

    public function testAssertEnoughCoinsForThrowsNotEnoughCoins(): void
    {
        $walletId = WalletIdMother::create();
        $name     = NameMother::create();
        $coins    = WalletCoinsMother::create(5); // Not enough for products
        $wallet   = new Wallet($walletId, $name, $coins);

        $productId = new ProductId(new TestUuidValue(), '123e4567-e89b-12d3-a456-426614174000');
        $product   = new Product(
            $productId,
            new Name('TestProduct'),
            Price::fromFloat(10),
            new Quantity(1)
        );
        $products        = new ProductCollection([$product]);
        $productQuantity = ['123e4567-e89b-12d3-a456-426614174000' => 1];

        $this->expectException(NotEnoughCoins::class);
        $wallet->assertEnoughCoinsFor($products, $productQuantity);
    }

    #[\PHPUnit\Framework\Attributes\DoesNotPerformAssertions]
    public function testAssertEnoughCoinsForDoesNotThrow(): void
    {
        $walletId = WalletIdMother::create();
        $name     = NameMother::create();
        $coins    = WalletCoinsMother::create(20);
        $wallet   = new Wallet($walletId, $name, $coins);

        $productId = new ProductId(new TestUuidValue(), '123e4567-e89b-12d3-a456-426614174000');
        $product   = new Product(
            $productId,
            new Name('TestProduct'),
            Price::fromFloat(10),
            new Quantity(1)
        );
        $products        = new ProductCollection([$product]);
        $productQuantity = ['123e4567-e89b-12d3-a456-426614174000' => 1];

        $wallet->assertEnoughCoinsFor($products, $productQuantity);
    }

    public function testSubtractCoinsThrowsNotEnoughCoins(): void
    {
        $walletId = WalletIdMother::create();
        $name     = NameMother::create();
        $coins    = WalletCoinsMother::create(5);
        $wallet   = new Wallet($walletId, $name, $coins);

        $this->expectException(NotEnoughCoins::class);
        $wallet->subtractCoins(Coins::fromFloat(10));
    }

    public function testSubtractCoinsSubtractsCorrectly(): void
    {
        $walletId = WalletIdMother::create();
        $name     = NameMother::create();
        $coins    = WalletCoinsMother::create(20);
        $wallet   = new Wallet($walletId, $name, $coins);

        $wallet->subtractCoins(Coins::fromFloat(10));
        $this->assertEquals(10, $wallet->coins()->value());
    }
}
