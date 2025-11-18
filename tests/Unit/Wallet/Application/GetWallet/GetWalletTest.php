<?php

declare(strict_types = 1);

namespace Tests\Unit\Wallet\Application\GetWallet;

use PHPUnit\Framework\TestCase;
use Tests\Unit\Wallet\Domain\ValueObjects\NameMother;
use Tests\Unit\Wallet\Domain\ValueObjects\WalletCoinsMother;
use Tests\Unit\Wallet\Domain\ValueObjects\WalletIdMother;
use VendingMachine\Wallet\Application\GetWallet\GetWallet;
use VendingMachine\Wallet\Application\GetWallet\GetWalletResponse;
use VendingMachine\Wallet\Domain\Entities\Wallet;
use VendingMachine\Wallet\Domain\Error\WalletNotFound;
use VendingMachine\Wallet\Domain\Repositories\InMemoryWalletRepository;

class GetWalletTest extends TestCase
{
    private InMemoryWalletRepository $walletRepository;
    private GetWallet $sut;

    public function testExecuteReturnsGetWalletResponseWithWallet(): void
    {
        $walletId = WalletIdMother::create('123e4567-e89b-12d3-a456-426614174000');
        $name     = NameMother::create('TestWallet');
        $coins    = WalletCoinsMother::create(10);
        $wallet   = new Wallet($walletId, $name, $coins);

        $this->walletRepository->add($wallet);

        $response = $this->sut->execute($walletId);

        $this->assertInstanceOf(GetWalletResponse::class, $response);
        $this->assertSame($wallet, $response->wallet());
    }

    public function testExecuteThrowsExceptionWhenWalletNotFound(): void
    {
        $walletId = WalletIdMother::create('123e4567-e89b-12d3-a456-426614174000');
        $this->expectException(WalletNotFound::class);
        $this->sut->execute($walletId);
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->walletRepository = new InMemoryWalletRepository();
        $this->sut              = new GetWallet($this->walletRepository);
    }
}
