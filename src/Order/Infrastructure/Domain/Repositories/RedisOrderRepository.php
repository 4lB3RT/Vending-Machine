<?php

declare(strict_types = 1);

namespace VendingMachine\Order\Infrastructure\Domain\Repositories;

use Illuminate\Redis\Connections\Connection;
use VendingMachine\Order\Domain\Entities\Order;
use VendingMachine\Order\Domain\Errors\OrderNotFound;
use VendingMachine\Order\Domain\Repositories\OrderRepository;
use VendingMachine\Order\Domain\ValueObjects\OrderId;
use VendingMachine\Product\Domain\Collections\ProductCollection;
use VendingMachine\Product\Domain\Entities\Product;
use VendingMachine\Product\Domain\Errors\PriceCannotBeNegative;
use VendingMachine\Product\Domain\Errors\QuantityCannotBeNegative;
use VendingMachine\Product\Domain\ValueObjects\Name as ProductName;
use VendingMachine\Product\Domain\ValueObjects\Price;
use VendingMachine\Product\Domain\ValueObjects\ProductId;
use VendingMachine\Product\Domain\ValueObjects\Quantity;
use VendingMachine\Shared\Domain\Errors\CoinsCannotBeNegative;
use VendingMachine\Shared\Domain\Errors\InvalidCollectionType;
use VendingMachine\Shared\Domain\Errors\InvalidUuid;
use VendingMachine\Shared\Domain\Validators\UuidValue;
use VendingMachine\Wallet\Domain\Entities\Wallet;
use VendingMachine\Wallet\Domain\ValueObjects\Name;
use VendingMachine\Wallet\Domain\ValueObjects\WalletCoins;
use VendingMachine\Wallet\Domain\ValueObjects\WalletId;

final readonly class RedisOrderRepository implements OrderRepository
{
    public function __construct(
        private Connection $redis,
        private UuidValue $uuidValidator
    ) {
    }

    /**
     * @throws OrderNotFound
     * @throws InvalidCollectionType
     * @throws CoinsCannotBeNegative
     * @throws InvalidUuid
     * @throws PriceCannotBeNegative
     * @throws QuantityCannotBeNegative
     */
    public function findById(OrderId $id): Order
    {
        $key  = 'order:' . $id->value();
        $data = $this->redis->command('GET', [$key]);

        if (!$data) {
            throw OrderNotFound::create($id->value());
        }

        $orderArray = json_decode($data, true);
        $walletId   = $orderArray['wallet']['id'] ?? '';
        $wallet     = new Wallet(
            new WalletId($this->uuidValidator, $walletId),
            Name::fromString($orderArray['wallet']['name'] ?? ''),
            WalletCoins::fromFloat($orderArray['wallet']['coins'] ?? 0)
        );

        $productsRaw = $orderArray['products'] ?? [];

        $products = array_map(function ($product) {
            return new Product(
                new ProductId($this->uuidValidator, $product['id']),
                ProductName::fromString($product['name']),
                Price::fromFloat($product['price']),
                Quantity::fromInt($product['quantity'])
            );
        }, $productsRaw);

        return new Order(
            new OrderId($this->uuidValidator, $orderArray['id']),
            new ProductCollection($products),
            $wallet,
        );
    }

    public function save(Order $order): void
    {
        $key  = 'order:' . $order->id()->value();
        $data = json_encode($order->toArray());
        $this->redis->command('SET', [$key, $data]);
    }
}
