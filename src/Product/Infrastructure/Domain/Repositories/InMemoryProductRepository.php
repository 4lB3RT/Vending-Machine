<?php

declare(strict_types = 1);

namespace VendingMachine\Product\Infrastructure\Domain\Repositories;

use VendingMachine\Product\Domain\Collections\ProductCollection;
use VendingMachine\Product\Domain\Collections\ProductIdCollection;
use VendingMachine\Product\Domain\Entities\Product;
use VendingMachine\Product\Domain\Errors\ProductNotFound;
use VendingMachine\Product\Domain\Errors\ProductsNotFound;
use VendingMachine\Product\Domain\Repositories\ProductRepository;
use VendingMachine\Product\Domain\ValueObjects\ProductId;

class InMemoryProductRepository implements ProductRepository
{
    /** @var array<string, Product> */
    private array $products = [];

    public function add(Product $product): void
    {
        $this->products[$product->id()->value()] = $product;
    }

    public function save(Product $product): void
    {
        $this->products[$product->id()->value()] = $product;
    }

    /* @throws ProductNotFound */
    public function findById(ProductId $id): Product
    {
        if (!isset($this->products[$id->value()])) {
            throw ProductNotFound::create($id->value());
        }

        return $this->products[$id->value()];
    }

    public function get(): ProductCollection
    {
        return new ProductCollection(array_values($this->products));
    }

    public function getByIds(ProductIdCollection $productIds): ProductCollection
    {
        $ids      = array_map(fn (ProductId $id) => $id->value(), $productIds->items());
        $filtered = array_filter($this->products, fn ($product, $key) => in_array($key, $ids, true), ARRAY_FILTER_USE_BOTH);
        if (empty($filtered)) {
            throw ProductsNotFound::create($ids);
        }

        return new ProductCollection(array_values($filtered));
    }
}
