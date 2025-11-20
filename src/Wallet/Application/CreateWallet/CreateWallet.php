<?php

declare(strict_types = 1);

namespace VendingMachine\Wallet\Application\CreateWallet;

use VendingMachine\Shared\Domain\Validators\UuidValue;
use VendingMachine\Wallet\Domain\Entities\Wallet;
use VendingMachine\Wallet\Domain\Repositories\WalletRepository;
use VendingMachine\Wallet\Domain\ValueObjects\Name;
use VendingMachine\Wallet\Domain\ValueObjects\WalletCoins;
use VendingMachine\Wallet\Domain\ValueObjects\WalletId;

final readonly class CreateWallet
{
    public function __construct(
        private UuidValue $uuidValidator,
        private WalletRepository $eloquentWalletRepository,
        private WalletRepository $walletRepository
    ) {
    }

    public function execute(CreateWalletRequest $request): void
    {
        $walletId = new WalletId($this->uuidValidator, $request->walletId());
        $wallet   = new Wallet(
            $walletId,
            Name::fromString($request->name()),
            WalletCoins::fromFloat($request->coins())
        );

        $this->walletRepository->save($wallet);
        $this->eloquentWalletRepository->save($wallet);
    }
}
