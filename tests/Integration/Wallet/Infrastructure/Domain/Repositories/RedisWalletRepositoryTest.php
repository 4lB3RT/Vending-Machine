<?php

declare(strict_types = 1);

namespace Tests\Integration\Wallet\Infrastructure\Domain\Repositories;

use Illuminate\Redis\Connections\Connection;
use Illuminate\Redis\RedisManager;
use Tests\TestCase;
use Tests\Unit\Shared\Domain\Validators\TestUuidValue;
use VendingMachine\Wallet\Domain\Entities\Wallet;
use VendingMachine\Wallet\Domain\ValueObjects\WalletId;
use VendingMachine\Wallet\Infrastructure\Domain\Repositories\RedisWalletRepository;

final class RedisWalletRepositoryTest extends TestCase
{
    /** @var Connection */
    private $redis;

    public function testFindByIdReturnsWallet(): void
    {
        $validator  = new TestUuidValue();
        $walletId   = new WalletId($validator, '123e4567-e89b-12d3-a456-426614174000');
        $walletData = json_encode([['id' => $walletId->value(), 'coins' => 5]]);
        $this->redis->command('set', ['wallet:' . $walletId->value(), $walletData]);

        $repo   = new RedisWalletRepository($this->redis, $validator);
        $wallet = $repo->findById($walletId);

        $this->assertInstanceOf(Wallet::class, $wallet);
        $this->assertEquals('123e4567-e89b-12d3-a456-426614174000', $wallet->id()->value());
        $this->assertEquals(5, $wallet->coins()->value());
    }

    public function testFindByIdReturnsNullWhenNotFound(): void
    {
        $validator = new TestUuidValue();
        $walletId  = new WalletId($validator, '123e4567-e89b-12d3-a456-426614174000');
        $repo      = new RedisWalletRepository($this->redis, $validator);
        $wallet    = $repo->findById($walletId);

        $this->assertNull($wallet);
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->redis = app(RedisManager::class)->connection();
        $this->redis->command('flushdb');
    }
}
