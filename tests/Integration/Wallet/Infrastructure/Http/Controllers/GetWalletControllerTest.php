<?php

declare(strict_types = 1);

namespace Tests\Integration\Wallet\Infrastructure\Http\Controllers;

use Illuminate\Support\Facades\Redis;
use Tests\TestCase;

class GetWalletControllerTest extends TestCase
{
    public function testShouldReturnsWalletAsJson()
    {
        $walletId   = '123e4567-e89b-12d3-a456-426614174000';
        $walletData = json_encode([
            [
                'id'    => $walletId,
                'name'  => 'TestWallet',
                'coins' => 10,
            ],
        ]);
        Redis::command('set', ['wallet:' . $walletId, $walletData]);

        $response = $this->getJson('/api/wallets/' . $walletId);

        $response->assertStatus(200);
        $response->assertJsonFragment([
            'id'    => $walletId,
            'name'  => 'TestWallet',
            'coins' => 10,
        ]);
    }

    protected function setUp(): void
    {
        parent::setUp();
        Redis::command('flushdb');
    }
}
