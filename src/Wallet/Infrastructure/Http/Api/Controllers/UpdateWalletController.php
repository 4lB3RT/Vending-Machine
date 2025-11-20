<?php

declare(strict_types = 1);

namespace VendingMachine\Wallet\Infrastructure\Http\Api\Controllers;

use Illuminate\Http\Request;
use VendingMachine\Shared\Domain\Errors\CoinsCannotBeNegative;
use VendingMachine\Shared\Domain\Errors\InvalidUuid;
use VendingMachine\Shared\infrastructure\Http\Api\Services\JsonResponse;
use VendingMachine\Wallet\Application\UpdateWallet\UpdateWallet;
use VendingMachine\Wallet\Application\UpdateWallet\UpdateWalletRequest;
use VendingMachine\Wallet\Domain\Errors\WalletNotFound;

final readonly class UpdateWalletController
{
    public function __construct(
        private UpdateWallet $updateWallet
    ) {
    }

    /**
     * @throws CoinsCannotBeNegative
     * @throws InvalidUuid
     * @throws WalletNotFound
     */
    public function __invoke(string $walletId, Request $request): \Illuminate\Http\JsonResponse
    {
        $validated = $request->validate([
            'name'  => ['required', 'string'],
            'coins' => ['required', 'numeric'],
        ]);

        $updateRequest = new UpdateWalletRequest(
            $walletId,
            $validated['name'],
            (float) $validated['coins']
        );

        $this->updateWallet->execute($updateRequest);

        return JsonResponse::successResponse();
    }
}
