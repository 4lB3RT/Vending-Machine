<?php

declare(strict_types = 1);

namespace Tests\Integration\Wallet\Infrastructure\Domain\Repositories;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use VendingMachine\Wallet\Infrastructure\Models\WalletDao;
use VendingMachine\Wallet\Infrastructure\Domain\Repositories\EloquentWalletRepository;
use VendingMachine\Wallet\Domain\ValueObjects\WalletId;
use VendingMachine\Wallet\Domain\ValueObjects\Name;
use VendingMachine\Wallet\Domain\ValueObjects\WalletCoins;
use VendingMachine\Wallet\Domain\Entities\Wallet;
use VendingMachine\Wallet\Domain\Errors\WalletNotFound;
use Tests\Unit\Shared\Domain\Validators\TestUuidValue;

class EloquentWalletRepositoryTest extends TestCase
{
    use RefreshDatabase;

    #[\PHPUnit\Framework\Attributes\Test]
    public function testReturnsWalletWithCorrectValues()
    {
        WalletDao::factory()->create([
            'id'    => '123e4567-e89b-12d3-a456-426614174000',
            'name'  => 'TestWallet',
            'coins' => 100.0,
        ]);
        $repository = new EloquentWalletRepository();
        $wallet = $repository->findById(new WalletId(new TestUuidValue(), '123e4567-e89b-12d3-a456-426614174000'));

        $this->assertEquals('123e4567-e89b-12d3-a456-426614174000', $wallet->id()->value());
        $this->assertEquals('TestWallet', $wallet->name()->value());
        $this->assertEquals(100.0, $wallet->coins()->value());
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function testFindByIdThrowsWalletNotFound()
    {
        $repository = new EloquentWalletRepository();
        $walletId   = new WalletId(new TestUuidValue(), '123e4567-e89b-12d3-a456-426614174001');
        $this->expectException(WalletNotFound::class);
        $repository->findById($walletId);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function testSaveStoresWalletCorrectly()
    {
        $repository = new EloquentWalletRepository();
        $walletId   = new WalletId(new TestUuidValue(), '123e4567-e89b-12d3-a456-426614174001');
        $wallet     = new Wallet($walletId, Name::fromString('NewWallet'), WalletCoins::fromFloat(50.0));
        $repository->save($wallet);
        $wallet = $repository->findById($walletId);
        $this->assertNotNull($wallet);
        $this->assertEquals('NewWallet', $wallet->name()->value());
        $this->assertEquals(50.0, $wallet->coins()->value());
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function testShouldReturnsWalletListFromDatabase()
    {
        WalletDao::factory()->create([
            'id'    => '123e4567-e89b-12d3-a456-426614174000',
            'name'  => 'TestWallet',
            'coins' => 100.0,
        ]);

        $repository = new EloquentWalletRepository();
        $wallets = WalletDao::all();

        $this->assertCount(1, $wallets);
        $wallet = $wallets->first();
        $this->assertEquals('123e4567-e89b-12d3-a456-426614174000', $wallet->id);
        $this->assertEquals('TestWallet', $wallet->name);
        $this->assertEquals(100.0, $wallet->coins);
    }

    protected function setUp(): void
    {
        parent::setUp();

        WalletDao::query()->truncate();
    }
}
