<?php

declare(strict_types = 1);

namespace VendingMachine\Product\Application\GetProduct;

use VendingMachine\Product\Domain\Errors\PriceCannotBeNegative;
use VendingMachine\Product\Domain\Errors\QuantityCannotBeNegative;
use VendingMachine\Product\Domain\Repositories\ProductRepository;
use VendingMachine\Product\Domain\ValueObjects\ProductId;
use VendingMachine\Shared\Domain\Errors\InvalidUuid;
use VendingMachine\Shared\Domain\Validators\UuidValue;

final readonly class GetProduct
{
    public function __construct(
        private UuidValue $uuidValidator,
        private ProductRepository $productRepository,
    ) {
    }

    /**
     * @throws PriceCannotBeNegative
     * @throws QuantityCannotBeNegative
     * @throws InvalidUuid
     */
    public function execute(GetProductRequest $request): GetProductResponse
    {
        $productId = new ProductId($this->uuidValidator, $request->productId());
        $product   = $this->productRepository->findById($productId);

        return new GetProductResponse($product);
    }
}
