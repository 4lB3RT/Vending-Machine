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
    private InMemoryWalletRepository $walletRepository;
    private CreateWallet $sut;

    public function testCreateWalletSuccessfully(): void
    {
        $walletId = WalletIdMother::create('123e4567-e89b-12d3-a456-426614174000');
        $request  = new CreateWalletRequest($walletId->value(), 'NewName', 10);
        $this->sut->execute($request);

        $created = $this->walletRepository->findById($walletId);
        $this->assertEquals('NewName', $created->name()->value());
        $this->assertEquals(10, $created->coins()->value());
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->walletRepository = new InMemoryWalletRepository();
        $this->sut              = new CreateWallet(new TestUuidValue(), $this->walletRepository);
    }
}
