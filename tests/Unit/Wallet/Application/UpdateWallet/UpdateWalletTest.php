<?php

declare(strict_types = 1);

namespace Tests\Unit\Wallet\Application\UpdateWallet;

use PHPUnit\Framework\TestCase;
use Tests\Unit\Shared\Domain\Validators\TestUuidValue;
use Tests\Unit\Wallet\Domain\ValueObjects\NameMother;
use Tests\Unit\Wallet\Domain\ValueObjects\WalletCoinsMother;
use Tests\Unit\Wallet\Domain\ValueObjects\WalletIdMother;
use VendingMachine\Wallet\Application\UpdateWallet\UpdateWallet;
use VendingMachine\Wallet\Application\UpdateWallet\UpdateWalletRequest;
use VendingMachine\Wallet\Domain\Entities\Wallet;
use VendingMachine\Wallet\Domain\Error\WalletNotFound;
use VendingMachine\Wallet\Domain\Repositories\InMemoryWalletRepository;

final class UpdateWalletTest extends TestCase
{
    private InMemoryWalletRepository $walletRepository;
    private UpdateWallet $sut;

    public function testUpdateWalletSuccessfully(): void
    {
        $walletId = WalletIdMother::create('123e4567-e89b-12d3-a456-426614174000');
        $name     = NameMother::create('OldName');
        $coins    = WalletCoinsMother::create(5);
        $wallet   = new Wallet($walletId, $name, $coins);
        $this->walletRepository->add($wallet);

        $request = new UpdateWalletRequest($walletId->value(), 'NewName', 10);
        $this->sut->execute($request);

        $updated = $this->walletRepository->findById($walletId);
        $this->assertEquals('NewName', $updated->name()->value());
        $this->assertEquals(10, $updated->coins()->value());
    }

    public function testUpdateWalletThrowsExceptionIfNotFound(): void
    {
        $walletId = WalletIdMother::create('123e4567-e89b-12d3-a456-426614174000');
        $request  = new UpdateWalletRequest($walletId->value(), 'NewName', 10);
        $this->expectException(WalletNotFound::class);
        $this->sut->execute($request);
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->walletRepository = new InMemoryWalletRepository();
        $this->sut              = new UpdateWallet(new TestUuidValue(), $this->walletRepository);
    }
}
