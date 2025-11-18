<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use VendingMachine\Product\Infrastructure\Http\Api\Controllers\GetProductsController;
use VendingMachine\Wallet\Infrastructure\Http\Api\Controllers\GetWalletController;
use VendingMachine\Wallet\Infrastructure\Http\Api\Controllers\UpdateWalletController;

Route::group(['prefix' => 'products'], function () {
    Route::get('/', [GetProductsController::class, '__invoke']);
});


Route::group(['prefix' => 'wallets'], function () {
    Route::group(['prefix' => '{uuid}'], function () {
        Route::get('/', [GetWalletController::class, '__invoke']);
        Route::put('/', [UpdateWalletController::class, '__invoke']);
    });
});
