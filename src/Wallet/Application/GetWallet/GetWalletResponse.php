<?php

declare(strict_types = 1);

namespace VendingMachine\Wallet\Application\GetWallet;

use VendingMachine\Wallet\Domain\Entities\Wallet;

final readonly class GetWalletResponse
{
    public function __construct(private Wallet $wallet)
    {
    }

    public function wallet(): Wallet
    {
        return $this->wallet;
    }
}
