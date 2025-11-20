<?php

declare(strict_types = 1);

namespace VendingMachine\Wallet\Domain\Repositories;

use VendingMachine\Shared\Domain\Errors\CoinsCannotBeNegative;
use VendingMachine\Shared\Domain\Errors\InvalidUuid;
use VendingMachine\Wallet\Domain\Entities\Wallet;
use VendingMachine\Wallet\Domain\Errors\WalletNotFound;
use VendingMachine\Wallet\Domain\ValueObjects\WalletId;

interface WalletRepository
{
    /**
     * @throws InvalidUuid
     * @throws CoinsCannotBeNegative
     * @throws WalletNotFound
     */
    public function findById(WalletId $id): Wallet;

    public function save(Wallet $wallet): void;
}
