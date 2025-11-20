<?php

declare(strict_types = 1);

namespace VendingMachine\Product\Infrastructure\Http\Api\Controllers;

use VendingMachine\Product\Application\GetProduct\GetProduct;
use VendingMachine\Product\Application\GetProduct\GetProductRequest;
use VendingMachine\Product\Domain\Errors\PriceCannotBeNegative;
use VendingMachine\Product\Domain\Errors\QuantityCannotBeNegative;
use VendingMachine\Shared\Domain\Errors\InvalidUuid;
use VendingMachine\Shared\infrastructure\Http\Api\Services\JsonResponse;

final readonly class GetProductController
{
    public function __construct(
        private GetProduct $getProduct
    ) {
    }

    /**
     * @throws PriceCannotBeNegative
     * @throws QuantityCannotBeNegative
     * @throws InvalidUuid
     */
    public function __invoke(string $productId): \Illuminate\Http\JsonResponse
    {
        $response = $this->getProduct->execute(new GetProductRequest($productId));

        return JsonResponse::successResponseWithContent($response->product()->toArray());
    }
}
