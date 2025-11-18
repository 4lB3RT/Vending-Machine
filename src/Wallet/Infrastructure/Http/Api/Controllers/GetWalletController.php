<?php

declare(strict_types = 1);

namespace VendingMachine\Wallet\Infrastructure\Http\Api\Controllers;

use VendingMachine\Shared\Domain\Errors\CoinsCannotBeNegative;
use VendingMachine\Shared\Domain\Errors\InvalidUuid;
use VendingMachine\Shared\Domain\Validators\UuidValue;
use VendingMachine\Shared\infrastructure\Http\Api\Services\JsonResponse;
use VendingMachine\Wallet\Application\GetWallet\GetWallet;
use VendingMachine\Wallet\Domain\ValueObjects\WalletId;

final readonly class GetWalletController
{
    public function __construct(
        private UuidValue $uuidValidator,
        private GetWallet $getWallet
    ) {
    }

    /**
     * @throws CoinsCannotBeNegative
     * @throws InvalidUuid
     */
    public function __invoke(
        string $walletId
    ): \Illuminate\Http\JsonResponse {
        $response = $this->getWallet->execute(new WalletId($this->uuidValidator, $walletId));

        return JsonResponse::successResponseWithContent($response->wallet()->toArray());
    }
}
