<?php

declare(strict_types = 1);

namespace VendingMachine\Wallet\Domain\Entities;

use VendingMachine\Wallet\Domain\ValueObjects\WalletCoins;
use VendingMachine\Wallet\Domain\ValueObjects\WalletId;

final readonly class Wallet
{
    public function __construct(
        private WalletId $id,
        private WalletCoins $coins
    ) {
    }

    public function id(): WalletId
    {
        return $this->id;
    }

    public function coins(): WalletCoins
    {
        return $this->coins;
    }
}
