<?php

declare(strict_types = 1);

namespace Tests\Integration\Product\Infrastructure\Domain\Repositories;

use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;
use Tests\Unit\Shared\Domain\Validators\TestUuidValue;
use VendingMachine\Product\Domain\Collections\ProductIdCollection;
use VendingMachine\Product\Domain\Entities\Product;
use VendingMachine\Product\Domain\Errors\ProductsNotFound;
use VendingMachine\Product\Domain\ValueObjects\ProductId;
use VendingMachine\Product\Domain\ValueObjects\Quantity;
use VendingMachine\Product\Infrastructure\Domain\Repositories\EloquentProductRepository;
use VendingMachine\Product\Infrastructure\Models\ProductDao;

class EloquentProductRepositoryTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function testShouldReturnsAllProductsFromDatabase()
    {
        $productDaos = ProductDao::factory()->count(3)->create();

        $repository = new EloquentProductRepository(new TestUuidValue());

        $result = $repository->get();

        $this->assertCount(3, $result->items());

        /** @var Product $product */
        foreach ($result->items() as $product) {
            $productDao = $productDaos->find($product->id()->value());

            $this->assertNotNull($productDao);
            $this->assertInstanceOf(ProductDao::class, $productDao);
            $this->assertEquals($productDao->id, $product->id()->value());
            $this->assertEquals($productDao->name, $product->name()->value());
            $this->assertEquals($productDao->price, $product->price()->value());
            $this->assertEquals($productDao->quantity, $product->quantity()->value());
        }
    }

    #[Test]
    public function testReturnsEmptyCollectionWhenNoProductsExist()
    {
        $repository = new EloquentProductRepository(new TestUuidValue());
        $result     = $repository->get();
        $this->assertCount(0, $result->items(), 'La colección debe estar vacía si no hay productos en la base de datos.');
    }

    #[Test]
    public function testReturnsProductWithCorrectValues()
    {
        ProductDao::factory()->create([
            'id'       => '123e4567-e89b-12d3-a456-426614174000',
            'name'     => 'CocaCola',
            'price'    => 1.50,
            'quantity' => 10,
        ]);

        $repository = new EloquentProductRepository(new TestUuidValue());
        $result     = $repository->get();

        $this->assertCount(1, $result->items());
        $product = $result->items()[0];
        $this->assertEquals('123e4567-e89b-12d3-a456-426614174000', $product->id()->value());
        $this->assertEquals('CocaCola', $product->name()->value());
        $this->assertEquals(1.50, $product->price()->value());
        $this->assertEquals(10, $product->quantity()->value());
    }

    public function testGetByIdsReturnsCorrectProducts()
    {
        $productDaos         = ProductDao::factory()->count(3)->create();
        $ids                 = $productDaos->pluck('id')->toArray();
        $repository          = new EloquentProductRepository(new TestUuidValue());
        $productIdCollection = new ProductIdCollection(
            array_map(fn ($id) => new ProductId(new TestUuidValue(), $id), $ids)
        );

        $result = $repository->getByIds($productIdCollection);
        $this->assertCount(3, $result->items());
        foreach ($result->items() as $product) {
            $this->assertContains($product->id()->value(), $ids);
        }
    }

    public function testGetByIdsThrowsProductsNotFound()
    {
        $repository          = new EloquentProductRepository(new TestUuidValue());
        $productIdCollection = new ProductIdCollection([
            new ProductId(new TestUuidValue(), '123e4567-e89b-12d3-a456-426614174000'),
        ]);

        $this->expectException(ProductsNotFound::class);
        $repository->getByIds($productIdCollection);
    }

    public function testGetReturnsAllProducts()
    {
        ProductDao::factory()->count(5)->create();
        $repository = new EloquentProductRepository(new TestUuidValue());
        $result     = $repository->get();

        $this->assertCount(5, $result->items());
    }

    public function testSaveUpdatesProductInDatabase()
    {
        $productDao = ProductDao::factory()->create([
            'id'       => '123e4567-e89b-12d3-a456-426614174000',
            'name'     => 'CocaCola',
            'price'    => 1.50,
            'quantity' => 10,
        ]);

        $repository = new EloquentProductRepository(new TestUuidValue());
        $product = $repository->findById(new ProductId(new TestUuidValue(), $productDao->id));

        $product->subtractQuantity(new Quantity(3));
        $repository->save($product);

        $updatedDao = ProductDao::find($productDao->id);
        $this->assertEquals(7, $updatedDao->quantity);
    }

    protected function setUp(): void
    {
        parent::setUp();

        ProductDao::query()->truncate();
    }
}
