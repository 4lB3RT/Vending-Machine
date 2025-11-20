<?php

declare(strict_types = 1);

namespace VendingMachine\Wallet\Domain\Entities;

use VendingMachine\Product\Domain\Collections\ProductCollection;
use VendingMachine\Product\Domain\Entities\Product;
use VendingMachine\Shared\Domain\Errors\CoinsCannotBeNegative;
use VendingMachine\Shared\Domain\ValueObjects\Coins;
use VendingMachine\Wallet\Domain\Errors\NotEnoughCoins;
use VendingMachine\Wallet\Domain\ValueObjects\Name;
use VendingMachine\Wallet\Domain\ValueObjects\WalletCoins;
use VendingMachine\Wallet\Domain\ValueObjects\WalletId;

final class Wallet
{
    public function __construct(
        private readonly WalletId $id,
        private Name $name,
        private WalletCoins $coins
    ) {
    }

    public function name(): Name
    {
        return $this->name;
    }

    public function coins(): WalletCoins
    {
        return $this->coins;
    }

    public function toArray(): array
    {
        return [
            'id'    => $this->id->value(),
            'name'  => $this->name->value(),
            'coins' => $this->coins->value(),
        ];
    }

    public function updateCoins(WalletCoins $coins): void
    {
        $this->coins = $coins;
    }

    public function updateName(Name $name): void
    {
        $this->name = $name;
    }

    /* @throws NotEnoughCoins */
    public function assertEnoughCoinsFor(ProductCollection $products): void
    {
        $totalPrice = 0;
        /** @var Product $product */
        foreach ($products->items() as $product) {
            $totalPrice += $product->price()->value();
        }

        if ($totalPrice > $this->coins->value()) {
            throw NotEnoughCoins::create($totalPrice, $this->coins->value());
        }
    }

    public function id(): WalletId
    {
        return $this->id;
    }

    /**
     * @throws NotEnoughCoins
     * @throws CoinsCannotBeNegative
     */
    public function subtractCoins(Coins $amount): void
    {
        $newValue = $this->coins->value() - $amount->value();
        if ($newValue < 0) {
            throw NotEnoughCoins::create($amount->value(), $this->coins->value());
        }

        $this->coins = WalletCoins::fromFloat($newValue);
    }
}
