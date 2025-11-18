<?php

declare(strict_types = 1);

namespace VendingMachine\Wallet\Application\UpdateWallet;

final readonly class UpdateWalletRequest
{
    public function __construct(
        private string $walletId,
        private string $name,
        private float $coins
    ) {
    }

    public function walletId(): string
    {
        return $this->walletId;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function coins(): float
    {
        return $this->coins;
    }
}
