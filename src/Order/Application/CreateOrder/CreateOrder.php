<?php

declare(strict_types = 1);

namespace VendingMachine\Order\Application\CreateOrder;

use VendingMachine\Order\Domain\Entities\Order;
use VendingMachine\Order\Domain\Repositories\OrderRepository;
use VendingMachine\Order\Domain\ValueObjects\OrderId;
use VendingMachine\Product\Domain\Collections\ProductCollection;
use VendingMachine\Product\Domain\Collections\ProductIdCollection;
use VendingMachine\Product\Domain\Entities\Product;
use VendingMachine\Product\Domain\Errors\PriceCannotBeNegative;
use VendingMachine\Product\Domain\Errors\ProductOutOfStock;
use VendingMachine\Product\Domain\Errors\ProductsNotFound;
use VendingMachine\Product\Domain\Errors\QuantityCannotBeNegative;
use VendingMachine\Product\Domain\Repositories\ProductRepository;
use VendingMachine\Product\Domain\ValueObjects\ProductId;
use VendingMachine\Product\Domain\ValueObjects\Quantity;
use VendingMachine\Shared\Domain\Errors\CoinsCannotBeNegative;
use VendingMachine\Shared\Domain\Errors\InvalidCollectionType;
use VendingMachine\Shared\Domain\Errors\InvalidUuid;
use VendingMachine\Shared\Domain\Validators\UuidValue;
use VendingMachine\Wallet\Domain\Errors\NotEnoughCoins;
use VendingMachine\Wallet\Domain\Errors\WalletNotFound;
use VendingMachine\Wallet\Domain\Repositories\WalletRepository;
use VendingMachine\Wallet\Domain\ValueObjects\WalletId;

final readonly class CreateOrder
{
    public function __construct(
        private UuidValue $uuidValidator,
        private OrderRepository $orderRepository,
        private ProductRepository $productRepository,
        private WalletRepository $walletRepository
    ) {
    }

    /**
     * @throws CoinsCannotBeNegative
     * @throws InvalidCollectionType
     * @throws InvalidUuid
     * @throws NotEnoughCoins
     * @throws PriceCannotBeNegative
     * @throws ProductOutOfStock
     * @throws QuantityCannotBeNegative
     * @throws WalletNotFound
     * @throws ProductsNotFound
     */
    public function execute(CreateOrderRequest $request): void
    {
        $orderId = new OrderId($this->uuidValidator, $request->orderId());

        $wallet = $this->walletRepository->findById(new WalletId($this->uuidValidator, $request->walletId()));

        $productIdsQuantity = [];
        foreach ($request->productIds() as $productId) {
            if (!isset($productIdsQuantity[$productId])) {
                $productIdsQuantity[$productId] = 1;
            } else {
                $productIdsQuantity[$productId]++;
            }
        }

        $productIds      = [];
        $productIdsValue = array_unique($request->productIds());
        foreach ($productIdsValue as $productIdValue) {
            $productIds[] = new ProductId($this->uuidValidator, $productIdValue);
        }

        $products = $this->productRepository->getByIds(new ProductIdCollection($productIds));

        $productsToOrder = [];
        foreach ($request->productIds() as $productId) {
            $productsToOrder[] = $products->findById(new ProductId($this->uuidValidator, $productId));
        }

        $productsToOrder = new ProductCollection($productsToOrder);
        $wallet->assertEnoughCoinsFor($productsToOrder);
        $this->validateProductStock($productsToOrder, $productIdsQuantity);

        /** @var Product $product */
        foreach ($products->items() as $product) {
            $quantityRequest = $productIdsQuantity[$product->id()->value()] ?? 0;
            $product->subtractQuantity(Quantity::fromInt($quantityRequest));
            $this->productRepository->save($product);
        }

        $totalPrice = $productsToOrder->totalPrice();
        $wallet->subtractCoins($totalPrice);
        $this->walletRepository->save($wallet);

        $order = new Order($orderId, $productsToOrder, $wallet);
        $this->orderRepository->save($order);
    }

    /* @throws ProductOutOfStock */
    private function validateProductStock(ProductCollection $products, array $productQuantity): void
    {
        /** @var Product $product */
        foreach ($products->items() as $product) {
            $product->assertStockAvailable($productQuantity[$product->id()->value()]);
        }
    }
}
