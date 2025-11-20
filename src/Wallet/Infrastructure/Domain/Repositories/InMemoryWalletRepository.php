<?php

declare(strict_types = 1);

namespace VendingMachine\Wallet\Infrastructure\Domain\Repositories;

use VendingMachine\Wallet\Domain\Entities\Wallet;
use VendingMachine\Wallet\Domain\Errors\WalletNotFound;
use VendingMachine\Wallet\Domain\Repositories\WalletRepository;
use VendingMachine\Wallet\Domain\ValueObjects\WalletId;

class InMemoryWalletRepository implements WalletRepository
{
    /** @var array<string, Wallet> */
    private array $wallets = [];

    public function add(Wallet $wallet): void
    {
        $this->wallets[$wallet->id()->value()] = $wallet;
    }

    public function findById(WalletId $id): Wallet
    {
        if (!isset($this->wallets[$id->value()])) {
            throw WalletNotFound::create($id->value());
        }

        return $this->wallets[$id->value()];
    }

    public function save(Wallet $wallet): void
    {
        $this->wallets[$wallet->id()->value()] = $wallet;
    }
}
