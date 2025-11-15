<?php

declare(strict_types = 1);

namespace Tests\Unit\Wallet\Domain\Entities;

use PHPUnit\Framework\TestCase;
use Tests\Unit\Wallet\Domain\ValueObjects\CoinsMother;
use Tests\Unit\Wallet\Domain\ValueObjects\WalletIdMother;
use VendingMachine\Wallet\Domain\Entities\Wallet;

final class WalletTest extends TestCase
{
    public function testCreatesWalletWithValidValues(): void
    {
        $walletId = WalletIdMother::create();
        $coins    = CoinsMother::create();

        $wallet = new Wallet($walletId, $coins);

        $this->assertSame($walletId, $wallet->id());
        $this->assertSame($coins, $wallet->coins());
    }
}
