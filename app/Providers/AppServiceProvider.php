<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use VendingMachine\Shared\Domain\Validators\Collection;
use VendingMachine\Shared\infrastructure\Domain\Validators\WebmozartCollection;
use VendingMachine\Shared\Domain\Validators\UuidValue;
use VendingMachine\Shared\infrastructure\Domain\Validators\RamseyUuidValue;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            Collection::class,
            WebmozartCollection::class
        );

        $this->app->bind(
            UuidValue::class,
            RamseyUuidValue::class
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
