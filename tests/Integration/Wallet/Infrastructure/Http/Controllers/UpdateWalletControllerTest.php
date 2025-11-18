<?php

declare(strict_types = 1);

namespace Tests\Integration\Wallet\Infrastructure\Http\Controllers;

use Illuminate\Support\Facades\Redis;
use Tests\TestCase;

class UpdateWalletControllerTest extends TestCase
{
    public function testShouldUpdateWalletAndReturnOk()
    {
        $walletId   = '123e4567-e89b-12d3-a456-426614174000';
        $walletData = json_encode([
            'id'    => $walletId,
            'name'  => 'OldWallet',
            'coins' => 5,
        ]);
        Redis::command('set', ['wallet:' . $walletId, $walletData]);

        $updatePayload = [
            'name'  => 'UpdatedWallet',
            'coins' => 20,
        ];

        $response = $this->putJson('/api/wallets/' . $walletId, $updatePayload);

        $response->assertStatus(200);
        $response->assertJsonFragment(['status' => 200]);

        $updatedWallet = json_decode(Redis::command('get', ['wallet:' . $walletId]), true);
        $this->assertEquals('UpdatedWallet', $updatedWallet['name']);
        $this->assertEquals(20, $updatedWallet['coins']);
    }

    protected function setUp(): void
    {
        parent::setUp();
        Redis::command('flushdb');
    }
}
