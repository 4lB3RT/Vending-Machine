<?php

declare(strict_types = 1);

namespace VendingMachine\Product\Infrastructure\Http\Api\Controllers;

use VendingMachine\Product\Application\GetProducts\GetProducts;
use VendingMachine\Product\Domain\Entities\Product;
use VendingMachine\Product\Domain\Errors\PriceCannotBeNegative;
use VendingMachine\Product\Domain\Errors\QuantityCannotBeNegative;
use VendingMachine\Shared\Domain\Errors\InvalidCollectionType;
use VendingMachine\Shared\Domain\Errors\InvalidUuid;
use VendingMachine\Shared\infrastructure\Http\Api\Services\JsonResponse;

final readonly class GetProductsController
{
    public function __construct(
        private GetProducts $getProducts
    ) {
    }

    /**
     * @throws InvalidCollectionType
     * @throws PriceCannotBeNegative
     * @throws QuantityCannotBeNegative
     * @throws InvalidUuid
     */
    public function __invoke(): \Illuminate\Http\JsonResponse
    {
        $response = $this->getProducts->execute();

        return JsonResponse::successResponseWithContent($response->products()->map(fn (Product $product) => $product->toArray()));
    }
}
