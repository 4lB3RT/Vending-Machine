<?php

declare(strict_types = 1);

namespace VendingMachine\Wallet\Infrastructure\Domain\Repositories;

use Illuminate\Redis\Connections\Connection;
use VendingMachine\Shared\Domain\Errors\CoinsCannotBeNegative;
use VendingMachine\Shared\Domain\Errors\InvalidUuid;
use VendingMachine\Shared\Domain\Validators\UuidValue;
use VendingMachine\Wallet\Domain\Entities\Wallet;
use VendingMachine\Wallet\Domain\Error\WalletNotFound;
use VendingMachine\Wallet\Domain\Repositories\WalletRepository;
use VendingMachine\Wallet\Domain\ValueObjects\Name;
use VendingMachine\Wallet\Domain\ValueObjects\WalletCoins;
use VendingMachine\Wallet\Domain\ValueObjects\WalletId;

final readonly class RedisWalletRepository implements WalletRepository
{
    public function __construct(private Connection $redis, private UuidValue $uuidValidator)
    {
    }

    /**
     * @throws InvalidUuid
     * @throws CoinsCannotBeNegative
     * @throws WalletNotFound
     */
    public function findById(WalletId $id): Wallet
    {
        $key  = 'wallet:' . $id->value();
        $data = $this->redis->command('GET', [$key]);

        if (!$data) {
            throw WalletNotFound::create($id->value());
        }

        $walletArray = json_decode($data, true)[0];

        return new Wallet(new WalletId($this->uuidValidator, $walletArray['id']), Name::fromString($walletArray['name']), WalletCoins::fromFloat($walletArray['coins']));
    }
}
