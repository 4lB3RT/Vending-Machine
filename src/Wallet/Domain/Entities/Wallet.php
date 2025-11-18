<?php

declare(strict_types = 1);

namespace VendingMachine\Wallet\Domain\Entities;

use VendingMachine\Wallet\Domain\ValueObjects\Name;
use VendingMachine\Wallet\Domain\ValueObjects\WalletCoins;
use VendingMachine\Wallet\Domain\ValueObjects\WalletId;

final readonly class Wallet
{
    public function __construct(
        private WalletId $id,
        private Name $name,
        private WalletCoins $coins
    ) {
    }

    public function id(): WalletId
    {
        return $this->id;
    }

    public function name(): Name
    {
        return $this->name;
    }

    public function coins(): WalletCoins
    {
        return $this->coins;
    }

    public function toArray(): array
    {
        return [
            'id'    => $this->id->value(),
            'name'  => $this->name->value(),
            'coins' => $this->coins->value(),
        ];
    }
}
