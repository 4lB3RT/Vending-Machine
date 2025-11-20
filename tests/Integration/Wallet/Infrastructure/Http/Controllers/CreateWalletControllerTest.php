<?php

declare(strict_types = 1);

namespace Tests\Integration\Wallet\Infrastructure\Http\Controllers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Redis;
use Tests\TestCase;
use VendingMachine\Product\Infrastructure\Models\ProductDao;
use VendingMachine\Wallet\Infrastructure\Models\WalletDao;

class CreateWalletControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testShouldCreateWalletAndReturnOk()
    {
        $walletId = '123e4567-e89b-12d3-a456-426614174000';
        $payload  = [
            'name'  => 'NewWallet',
            'coins' => 10,
        ];

        WalletDao::factory()->create([
            'id'    => $walletId,
            'name'  => $payload['name'],
            'coins' => $payload['coins'],
        ]);

        $response = $this->postJson('/api/wallets/' . $walletId, $payload);

        $response->assertStatus(200);
        $response->assertJsonFragment(['status' => 200]);

        $walletDao = WalletDao::query()->find($walletId);
        $this->assertNotNull($walletDao);
        $this->assertEquals($walletId, $walletDao->id);
        $this->assertEquals($payload['name'], $walletDao->name);
        $this->assertEquals($payload['coins'], $walletDao->coins);

        $createdWallet = json_decode(Redis::command('get', ['wallet:' . $walletId]), true);
        $this->assertEquals('NewWallet', $createdWallet['name']);
        $this->assertEquals(10, $createdWallet['coins']);
    }

    protected function setUp(): void
    {
        parent::setUp();

        ProductDao::query()->truncate();
        Redis::command('flushdb');
    }
}
