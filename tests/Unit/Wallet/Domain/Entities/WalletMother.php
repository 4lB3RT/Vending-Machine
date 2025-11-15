<?php

declare(strict_types = 1);

namespace Tests\Unit\Wallet\Domain\Entities;

use Tests\Unit\Wallet\Domain\ValueObjects\CoinsMother;
use Tests\Unit\Wallet\Domain\ValueObjects\WalletIdMother;
use VendingMachine\Wallet\Domain\Entities\Wallet;

final class WalletMother
{
    public static function create(
        ?string $uuid = null,
        ?float $coins = null
    ): Wallet {
        return new Wallet(
            WalletIdMother::create($uuid),
            CoinsMother::create($coins)
        );
    }
}
