<?php

declare(strict_types = 1);

namespace VendingMachine\Product\Infrastructure\Domain\Repositories;

use VendingMachine\Product\Domain\Collections\ProductCollection;
use VendingMachine\Product\Domain\Entities\Product;
use VendingMachine\Product\Domain\Errors\PriceCannotBeNegative;
use VendingMachine\Product\Domain\Errors\QuantityCannotBeNegative;
use VendingMachine\Product\Domain\Repositories\ProductRepository;
use VendingMachine\Product\Domain\ValueObjects\Name;
use VendingMachine\Product\Domain\ValueObjects\Price;
use VendingMachine\Product\Domain\ValueObjects\ProductId;
use VendingMachine\Product\Domain\ValueObjects\Quantity;
use VendingMachine\Product\Infrastructure\Models\ProductDao;
use VendingMachine\Shared\Domain\Errors\InvalidCollectionType;
use VendingMachine\Shared\Domain\Errors\InvalidUuid;
use VendingMachine\Shared\Domain\Validators\UuidValue;

final readonly class EloquentProductRepository implements ProductRepository
{
    public function __construct(private UuidValue $uuidValidator)
    {
    }

    /**
     * @throws InvalidCollectionType
     * @throws PriceCannotBeNegative
     * @throws QuantityCannotBeNegative
     * @throws InvalidUuid
     */
    public function get(): ProductCollection
    {
        $productDaos = ProductDao::all();

        $products = [];
        /** @var ProductDao $productDao */
        foreach ($productDaos as $productDao) {
            $products[] = new Product(
                new ProductId($this->uuidValidator, $productDao->id),
                Name::fromString($productDao->name),
                Price::fromFloat($productDao->price),
                Quantity::fromInt($productDao->quantity)
            );
        }

        return new ProductCollection($products);
    }
}
