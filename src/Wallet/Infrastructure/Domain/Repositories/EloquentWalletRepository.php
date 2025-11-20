<?php

declare(strict_types = 1);

namespace VendingMachine\Wallet\Infrastructure\Domain\Repositories;

use VendingMachine\Wallet\Domain\Repositories\WalletRepository;
use VendingMachine\Wallet\Domain\Entities\Wallet;
use VendingMachine\Wallet\Domain\ValueObjects\WalletId;
use VendingMachine\Wallet\Domain\ValueObjects\Name;
use VendingMachine\Wallet\Domain\ValueObjects\WalletCoins;
use VendingMachine\Wallet\Domain\Errors\WalletNotFound;
use VendingMachine\Shared\Domain\Errors\CoinsCannotBeNegative;
use VendingMachine\Wallet\Infrastructure\Models\WalletDao;

final class EloquentWalletRepository implements WalletRepository
{
    /**
     * @throws CoinsCannotBeNegative
     * @throws WalletNotFound
     */
    public function findById(WalletId $id): Wallet
    {
        $walletDao = WalletDao::query()->find($id->value());
        if (!$walletDao) {
            throw WalletNotFound::create($id->value());
        }

        return new Wallet(
            $id,
            Name::fromString($walletDao->name),
            WalletCoins::fromFloat($walletDao->coins)
        );
    }

    public function save(Wallet $wallet): void
    {
        WalletDao::query()->updateOrCreate(
            ['id' => $wallet->id()->value()],
            [
                'name'  => $wallet->name()->value(),
                'coins' => $wallet->coins()->value(),
            ]
        );
    }
}
