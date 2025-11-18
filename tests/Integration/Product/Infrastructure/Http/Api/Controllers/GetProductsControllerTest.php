<?php

declare(strict_types = 1);

namespace Tests\Integration\Product\Infrastructure\Http\Api\Controllers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use VendingMachine\Product\Infrastructure\Models\ProductDao;

class GetProductsControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testShouldReturnsProductsListAsJson()
    {
        ProductDao::factory()->create([
            'id'       => '123e4567-e89b-12d3-a456-426614174000',
            'name'     => 'CocaCola',
            'price'    => 1.50,
            'quantity' => 10,
        ]);

        $response = $this->getJson('/api/products');

        $response->assertStatus(200);
        $response->assertJsonFragment([
            'id'       => '123e4567-e89b-12d3-a456-426614174000',
            'name'     => 'CocaCola',
            'price'    => 1.50,
            'quantity' => 10,
        ]);
    }

    protected function setUp(): void
    {
        parent::setUp();

        ProductDao::query()->truncate();
    }
}
