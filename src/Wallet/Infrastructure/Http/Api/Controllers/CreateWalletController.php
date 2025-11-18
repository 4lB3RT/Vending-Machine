<?php

declare(strict_types = 1);

namespace VendingMachine\Wallet\Infrastructure\Http\Api\Controllers;

use Illuminate\Http\Request;
use VendingMachine\Shared\infrastructure\Http\Api\Services\JsonResponse;
use VendingMachine\Wallet\Application\CreateWallet\CreateWallet;
use VendingMachine\Wallet\Application\CreateWallet\CreateWalletRequest;

final readonly class CreateWalletController
{
    public function __construct(
        private CreateWallet $createWallet
    ) {
    }

    public function __invoke(string $walletId, Request $request): \Illuminate\Http\JsonResponse
    {
        $validated = $request->validate([
            'name'  => ['required', 'string'],
            'coins' => ['required', 'numeric'],
        ]);

        $createRequest = new CreateWalletRequest(
            $walletId,
            $validated['name'],
            (float) $validated['coins']
        );

        $this->createWallet->execute($createRequest);

        return JsonResponse::successResponse();
    }
}
