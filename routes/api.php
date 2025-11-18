<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use VendingMachine\Product\Infrastructure\Http\Api\Controllers\GetProductsController;
use VendingMachine\Wallet\Infrastructure\Http\Api\Controllers\GetWalletController;

Route::group(['prefix' => 'products'], function () {
    Route::get('/', [GetProductsController::class, '__invoke']);
});


Route::group(['prefix' => 'wallets'], function () {
    Route::get('/{uuid}', [GetWalletController::class, '__invoke']);
});
