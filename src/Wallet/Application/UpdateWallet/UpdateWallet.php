<?php

declare(strict_types = 1);

namespace VendingMachine\Wallet\Application\UpdateWallet;

use VendingMachine\Shared\Domain\Errors\CoinsCannotBeNegative;
use VendingMachine\Shared\Domain\Errors\InvalidUuid;
use VendingMachine\Shared\Domain\Validators\UuidValue;
use VendingMachine\Wallet\Domain\Error\WalletNotFound;
use VendingMachine\Wallet\Domain\Repositories\WalletRepository;
use VendingMachine\Wallet\Domain\ValueObjects\Name;
use VendingMachine\Wallet\Domain\ValueObjects\WalletCoins;
use VendingMachine\Wallet\Domain\ValueObjects\WalletId;

final readonly class UpdateWallet
{
    public function __construct(
        private UuidValue $uuidValidator,
        private WalletRepository $walletRepository
    ) {
    }

    /**
     * @throws CoinsCannotBeNegative
     * @throws InvalidUuid
     * @throws WalletNotFound
     */
    public function execute(UpdateWalletRequest $request): void
    {
        $walletId = new WalletId($this->uuidValidator, $request->walletId());

        $wallet = $this->walletRepository->findById($walletId);

        $wallet->updateName(Name::fromString($request->name()));
        $wallet->updateCoins(WalletCoins::fromFloat($request->coins()));

        $this->walletRepository->save($wallet);
    }
}
