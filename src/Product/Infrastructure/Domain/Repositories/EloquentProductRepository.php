<?php

declare(strict_types = 1);

namespace VendingMachine\Product\Infrastructure\Domain\Repositories;

use Exception;
use VendingMachine\Product\Domain\Collections\ProductCollection;
use VendingMachine\Product\Domain\Collections\ProductIdCollection;
use VendingMachine\Product\Domain\Entities\Product;
use VendingMachine\Product\Domain\Errors\PriceCannotBeNegative;
use VendingMachine\Product\Domain\Errors\ProductsNotFound;
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
     * @throws InvalidUuid
     * @throws QuantityCannotBeNegative
     * @throws ProductsNotFound
     */
    public function getByIds(ProductIdCollection $productIds): ProductCollection
    {
        $ids         = array_map(fn (ProductId $id) => $id->value(), $productIds->items());
        $productDaos = ProductDao::whereIn('id', $ids)->get();
        if ($productDaos->isEmpty()) {
            throw ProductsNotFound::create($ids);
        }

        $products = [];
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

    /**
     * @throws PriceCannotBeNegative
     * @throws InvalidUuid
     * @throws QuantityCannotBeNegative
     */
    public function findById(ProductId $id): Product
    {
        $productDao = ProductDao::find($id->value());
        if (!$productDao) {
            throw new Exception("Product not found: {$id->value()}");
        }

        return new Product(
            new ProductId($this->uuidValidator, $productDao->id),
            Name::fromString($productDao->name),
            Price::fromFloat($productDao->price),
            Quantity::fromInt($productDao->quantity)
        );
    }

    public function save(Product $product): void
    {
        ProductDao::query()->updateOrCreate(
            ['id' => $product->id()->value()],
            [
                'name'     => $product->name()->value(),
                'price'    => $product->price()->value(),
                'quantity' => $product->quantity()->value(),
            ]
        );
    }
}
