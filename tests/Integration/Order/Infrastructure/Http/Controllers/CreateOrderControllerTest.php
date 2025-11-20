<?php

declare(strict_types = 1);

namespace Tests\Integration\Order\Infrastructure\Http\Controllers;

use Illuminate\Support\Facades\Redis;
use Tests\TestCase;
use VendingMachine\Product\Infrastructure\Models\ProductDao;

class CreateOrderControllerTest extends TestCase
{
    public function testShouldCreateOrderAndReturnOk()
    {
        $orderId   = '123e4567-e89b-12d3-a456-426614174999';
        $productId = '123e4567-e89b-12d3-a456-426614174777';

        ProductDao::factory()->create([
            'id'       => $productId,
            'name'     => 'TestProduct',
            'price'    => 10,
            'quantity' => 10,
        ]);

        $walletId      = '123e4567-e89b-12d3-a456-426614174888';
        $walletPayload = [
            'name'  => 'TestWallet',
            'coins' => 100,
        ];
        $this->postJson('/api/wallets/' . $walletId, $walletPayload)->assertStatus(200);
        $payload = [
            'productIds' => [$productId],
            'walletId'   => $walletId,
        ];
        $response = $this->postJson('/api/orders/' . $orderId, $payload);
        $response->assertStatus(200);
        $response->assertJsonFragment(['status' => 200]);
        $createdOrder = json_decode(Redis::command('get', ['order:' . $orderId]), true);
        $this->assertEquals($orderId, $createdOrder['id']);
        $this->assertEquals($walletId, $createdOrder['wallet']['id']);
    }

    public function testShouldReturnWalletNotFoundError()
    {
        $orderId   = '123e4567-e89b-12d3-a456-426614174990';
        $productId = '123e4567-e89b-12d3-a456-426614174779';
        ProductDao::factory()->create([
            'id'       => $productId,
            'name'     => 'TestProduct',
            'price'    => 10,
            'quantity' => 10,
        ]);
        $payload = [
            'productIds' => [$productId],
            'walletId'   => '123e4567-e89b-12d3-a456-426614179999',
        ];
        $response = $this->postJson('/api/orders/' . $orderId, $payload);

        $response->assertStatus(404);
        $this->assertStringContainsString('Wallet not found', $response->json('error'));
    }

    public function testShouldReturnNotEnoughCoinsError()
    {
        $orderId   = '123e4567-e89b-12d3-a456-426614174991';
        $productId = '123e4567-e89b-12d3-a456-426614174780';
        ProductDao::factory()->create([
            'id'       => $productId,
            'name'     => 'ExpensiveProduct',
            'price'    => 1000,
            'quantity' => 10,
        ]);
        $walletId      = '123e4567-e89b-12d3-a456-426614174889';
        $walletPayload = [
            'name'  => 'TestWallet',
            'coins' => 10,
        ];
        $this->postJson('/api/wallets/' . $walletId, $walletPayload)->assertStatus(200);
        $payload = [
            'productIds' => [$productId],
            'walletId'   => $walletId,
        ];
        $response = $this->postJson('/api/orders/' . $orderId, $payload);

        $response->assertStatus(400);
        $this->assertStringContainsString('Not enough coins', $response->json('error'));
    }

    public function testShouldReturnProductOutOfStockError()
    {
        $orderId   = '123e4567-e89b-12d3-a456-426614174992';
        $productId = '123e4567-e89b-12d3-a456-426614174781';
        ProductDao::factory()->create([
            'id'       => $productId,
            'name'     => 'LimitedProduct',
            'price'    => 10,
            'quantity' => 0,
        ]);
        $walletId      = '123e4567-e89b-12d3-a456-426614174890';
        $walletPayload = [
            'name'  => 'TestWallet',
            'coins' => 100,
        ];
        $this->postJson('/api/wallets/' . $walletId, $walletPayload)->assertStatus(200);
        $payload = [
            'productIds' => [$productId],
            'walletId'   => $walletId,
        ];
        $response = $this->postJson('/api/orders/' . $orderId, $payload);
        $response->assertStatus(400);
        $this->assertStringContainsString('out of stock', $response->json('error'));
    }

    public function testShouldReturnProductsNotFoundError()
    {
        $orderId       = '123e4567-e89b-12d3-a456-426614174993';
        $productId     = '123e4567-e89b-12d3-a456-426614174782';
        $walletId      = '123e4567-e89b-12d3-a456-426614174891';
        $walletPayload = [
            'name'  => 'TestWallet',
            'coins' => 100,
        ];
        $this->postJson('/api/wallets/' . $walletId, $walletPayload)->assertStatus(200);
        $payload = [
            'productIds' => [$productId],
            'walletId'   => $walletId,
        ];
        $response = $this->postJson('/api/orders/' . $orderId, $payload);
        $response->assertStatus(404);
        $this->assertStringContainsString('Products not found', $response->json('error'));
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('migrate');
        Redis::command('flushdb');
    }
}
