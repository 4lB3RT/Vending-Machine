<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use VendingMachine\Order\Infrastructure\Http\Api\Controllers\CreateOrderController;
use VendingMachine\Product\Infrastructure\Http\Api\Controllers\GetProductController;
use VendingMachine\Product\Infrastructure\Http\Api\Controllers\GetProductsController;
use VendingMachine\Wallet\Infrastructure\Http\Api\Controllers\CreateWalletController;
use VendingMachine\Wallet\Infrastructure\Http\Api\Controllers\GetWalletController;
use VendingMachine\Wallet\Infrastructure\Http\Api\Controllers\UpdateWalletController;

Route::group(['prefix' => 'products'], function () {
    Route::get('/', [GetProductsController::class, '__invoke']);
    Route::group(['prefix' => '{uuid}'], function () {
        Route::get('/', [GetProductController::class, '__invoke']);
    });
});


Route::group(['prefix' => 'wallets'], function () {
    Route::group(['prefix' => '{uuid}'], function () {
        Route::get('/', [GetWalletController::class, '__invoke']);
        Route::put('/', [UpdateWalletController::class, '__invoke']);
        Route::post('/', [CreateWalletController::class, '__invoke']);
    });
});

Route::group(['prefix' => 'orders'], function () {
    Route::group(['prefix' => '{uuid}'], function () {
        Route::post('/', [CreateOrderController::class, '__invoke']);
    });
});
