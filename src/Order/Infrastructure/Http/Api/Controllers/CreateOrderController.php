<?php

declare(strict_types = 1);

namespace VendingMachine\Order\Infrastructure\Http\Api\Controllers;

use Illuminate\Http\Request;
use VendingMachine\Order\Application\CreateOrder\CreateOrder;
use VendingMachine\Order\Application\CreateOrder\CreateOrderRequest;
use VendingMachine\Product\Domain\Errors\PriceCannotBeNegative;
use VendingMachine\Product\Domain\Errors\ProductOutOfStock;
use VendingMachine\Product\Domain\Errors\ProductsNotFound;
use VendingMachine\Product\Domain\Errors\QuantityCannotBeNegative;
use VendingMachine\Shared\Domain\Errors\CoinsCannotBeNegative;
use VendingMachine\Shared\Domain\Errors\InvalidCollectionType;
use VendingMachine\Shared\Domain\Errors\InvalidUuid;
use VendingMachine\Shared\infrastructure\Http\Api\Services\JsonResponse;
use VendingMachine\Wallet\Domain\Errors\NotEnoughCoins;
use VendingMachine\Wallet\Domain\Errors\WalletNotFound;

final readonly class CreateOrderController
{
    public function __construct(private CreateOrder $createOrder)
    {
    }

    /**
     * @throws CoinsCannotBeNegative
     * @throws InvalidCollectionType
     * @throws InvalidUuid
     * @throws NotEnoughCoins
     * @throws ProductOutOfStock
     * @throws PriceCannotBeNegative
     * @throws ProductsNotFound
     * @throws QuantityCannotBeNegative
     * @throws WalletNotFound
     */
    public function __invoke(string $orderId, Request $request): \Illuminate\Http\JsonResponse
    {
        $validated = $request->validate([
            'productIds' => ['required', 'array'],
            'walletId'   => ['required', 'string'],
        ]);

        $createRequest = new CreateOrderRequest(
            $orderId,
            $validated['productIds'],
            $validated['walletId'],
        );

        $this->createOrder->execute($createRequest);

        return JsonResponse::successResponse();
    }
}
