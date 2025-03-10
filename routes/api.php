<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {

    Route::prefix('auth')->group(function () {
        Route::post('login', [AuthController::class, 'login']);
    });

    Route::middleware(['auth:sanctum'])->group(function () {

        Route::prefix('users')->group(function () {
            Route::get('/', [UserController::class, 'index']);
            Route::get('/{id}', [UserController::class, 'show']);
            Route::post('/', [UserController::class, 'store']);
            Route::put('/{id}', [UserController::class, 'update']);
            Route::delete('/{id}', [UserController::class, 'destroy']);
        });

        Route::prefix('orders')->group(function () {
            Route::get('/', [OrderController::class, 'index']);
            Route::get('/{id}', [OrderController::class, 'show']);

            Route::post('/', [OrderController::class, 'store']);
            Route::post('/{id}/take', [OrderController::class, 'orderTake']);
            Route::post('/{id}/complete', [OrderController::class, 'orderComplete']);

            Route::put('/{id}', [OrderController::class, 'update']);
            Route::delete('/{id}', [OrderController::class, 'destroy']);
        });

    });

});
