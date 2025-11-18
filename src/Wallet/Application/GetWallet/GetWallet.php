<?php

declare(strict_types = 1);

namespace VendingMachine\Wallet\Application\GetWallet;

use VendingMachine\Shared\Domain\Errors\CoinsCannotBeNegative;
use VendingMachine\Shared\Domain\Errors\InvalidUuid;
use VendingMachine\Wallet\Domain\Repositories\WalletRepository;
use VendingMachine\Wallet\Domain\ValueObjects\WalletId;

final readonly class GetWallet
{
    public function __construct(
        private WalletRepository $walletRepository
    ) {
    }

    /**
     * @throws CoinsCannotBeNegative
     * @throws InvalidUuid
     */
    public function execute(WalletId $id): GetWalletResponse
    {
        $wallet = $this->walletRepository->findById($id);

        return new GetWalletResponse($wallet);
    }
}
