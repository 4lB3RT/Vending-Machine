<?php

declare(strict_types = 1);

namespace VendingMachine\Product\Application\GetProducts;

use VendingMachine\Product\Domain\Errors\PriceCannotBeNegative;
use VendingMachine\Product\Domain\Errors\QuantityCannotBeNegative;
use VendingMachine\Product\Domain\Repositories\ProductRepository;
use VendingMachine\Shared\Domain\Errors\InvalidCollectionType;
use VendingMachine\Shared\Domain\Errors\InvalidUuid;

final readonly class GetProducts
{
    public function __construct(
        private ProductRepository $productRepository
    ) {
    }

    /**
     * @throws InvalidCollectionType
     * @throws PriceCannotBeNegative
     * @throws QuantityCannotBeNegative
     * @throws InvalidUuid
     */
    public function execute(): GetProductsResponse
    {
        $products = $this->productRepository->get();

        return new GetProductsResponse($products);
    }
}
