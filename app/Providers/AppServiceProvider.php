<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use VendingMachine\Product\Domain\Repositories\ProductRepository;
use VendingMachine\Product\Infrastructure\Domain\Repositories\EloquentProductRepository;
use VendingMachine\Shared\Domain\Validators\UuidValue;
use VendingMachine\Shared\infrastructure\Domain\Validators\RamseyUuidValue;
use VendingMachine\Wallet\Domain\Repositories\WalletRepository;
use VendingMachine\Wallet\Infrastructure\Domain\Repositories\RedisWalletRepository;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            UuidValue::class,
            RamseyUuidValue::class
        );

        $this->app->bind(
            ProductRepository::class,
            EloquentProductRepository::class
        );

        $this->app->bind(
            WalletRepository::class,
            RedisWalletRepository::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
