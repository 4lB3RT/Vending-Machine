<?php

declare(strict_types = 1);

namespace Tests\Integration\Wallet\Infrastructure\Http\Controllers;

use Illuminate\Support\Facades\Redis;
use Tests\TestCase;

class CreateWalletControllerTest extends TestCase
{
    public function testShouldCreateWalletAndReturnOk()
    {
        $walletId = '123e4567-e89b-12d3-a456-426614174000';
        $payload  = [
            'name'  => 'NewWallet',
            'coins' => 10,
        ];

        $response = $this->postJson('/api/wallets/' . $walletId, $payload);

        $response->assertStatus(200);
        $response->assertJsonFragment(['status' => 200]);

        $createdWallet = json_decode(Redis::command('get', ['wallet:' . $walletId]), true);
        $this->assertEquals('NewWallet', $createdWallet['name']);
        $this->assertEquals(10, $createdWallet['coins']);
    }

    protected function setUp(): void
    {
        parent::setUp();
        Redis::command('flushdb');
    }
}
