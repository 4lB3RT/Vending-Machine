<?php

declare(strict_types = 1);

namespace VendingMachine\Product\Domain\Repositories;

use VendingMachine\Product\Domain\Collections\ProductCollection;
use VendingMachine\Product\Domain\Collections\ProductIdCollection;
use VendingMachine\Product\Domain\Entities\Product;
use VendingMachine\Product\Domain\Errors\PriceCannotBeNegative;
use VendingMachine\Product\Domain\Errors\ProductsNotFound;
use VendingMachine\Product\Domain\Errors\QuantityCannotBeNegative;
use VendingMachine\Product\Domain\ValueObjects\ProductId;
use VendingMachine\Shared\Domain\Errors\InvalidCollectionType;
use VendingMachine\Shared\Domain\Errors\InvalidUuid;

interface ProductRepository
{
    /**
     * @throws InvalidCollectionType
     * @throws PriceCannotBeNegative
     * @throws QuantityCannotBeNegative
     * @throws InvalidUuid
     */
    public function get(): ProductCollection;

    /**
     * @throws PriceCannotBeNegative
     * @throws InvalidUuid
     * @throws QuantityCannotBeNegative
     */
    public function findById(ProductId $id): Product;

    /**
     * @throws InvalidCollectionType
     * @throws PriceCannotBeNegative
     * @throws InvalidUuid
     * @throws QuantityCannotBeNegative
     * @throws ProductsNotFound
     */
    public function getByIds(ProductIdCollection $productIds): ProductCollection;
}
