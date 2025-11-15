<?php

declare(strict_types = 1);

namespace VendingMachine\Wallet\Domain\Entities;

use VendingMachine\Wallet\Domain\ValueObjects\Coins;
use VendingMachine\Wallet\Domain\ValueObjects\WalletId;

final readonly class Wallet
{
    public function __construct(
        private WalletId $id,
        private Coins $coins
    ) {
    }

    public function id(): WalletId
    {
        return $this->id;
    }

    public function coins(): Coins
    {
        return $this->coins;
    }
}
