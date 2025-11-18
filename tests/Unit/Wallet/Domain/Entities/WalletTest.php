<?php

declare(strict_types = 1);

namespace Tests\Unit\Wallet\Domain\Entities;

use PHPUnit\Framework\TestCase;
use Tests\Unit\Wallet\Domain\ValueObjects\NameMother;
use Tests\Unit\Wallet\Domain\ValueObjects\WalletCoinsMother;
use Tests\Unit\Wallet\Domain\ValueObjects\WalletIdMother;
use VendingMachine\Wallet\Domain\Entities\Wallet;

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
}
