<?php

declare(strict_types = 1);

namespace Tests\Unit\Wallet\Application\CreateWallet;

use PHPUnit\Framework\TestCase;
use Tests\Unit\Shared\Domain\Validators\TestUuidValue;
use Tests\Unit\Wallet\Domain\ValueObjects\WalletIdMother;
use VendingMachine\Wallet\Application\CreateWallet\CreateWallet;
use VendingMachine\Wallet\Application\CreateWallet\CreateWalletRequest;
use VendingMachine\Wallet\Infrastructure\Domain\Repositories\InMemoryWalletRepository;

final class CreateWalletTest extends TestCase
{
    private InMemoryWalletRepository $backupWalletRepository;
    private InMemoryWalletRepository $dynamicWalletRepository;
    private CreateWallet $sut;

    public function testCreateWalletSuccessfully(): void
    {
        $walletId = WalletIdMother::create('123e4567-e89b-12d3-a456-426614174000');
        $request  = new CreateWalletRequest($walletId->value(), 'NewName', 10);
        $this->sut->execute($request);

        $createdDynamic = $this->dynamicWalletRepository->findById($walletId);
        $createdBackup = $this->backupWalletRepository->findById($walletId);

        $this->assertEquals('NewName', $createdDynamic->name()->value());
        $this->assertEquals(10, $createdDynamic->coins()->value());

        $this->assertEquals('NewName', $createdBackup->name()->value());
        $this->assertEquals(10, $createdBackup->coins()->value());
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->backupWalletRepository = new InMemoryWalletRepository();
        $this->dynamicWalletRepository = new InMemoryWalletRepository();
        $this->sut              = new CreateWallet(new TestUuidValue(), $this->backupWalletRepository, $this->dynamicWalletRepository);
    }
}
