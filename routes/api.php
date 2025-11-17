<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use VendingMachine\Product\Infrastructure\Http\Api\Controllers\GetProductsController;

Route::group(['prefix' => 'products'], function () {
    Route::get('/', [GetProductsController::class, '__invoke']);
});
