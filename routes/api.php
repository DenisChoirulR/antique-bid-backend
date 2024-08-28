<?php

use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\BidController;
use App\Http\Controllers\Api\ItemController;
use App\Http\Controllers\Api\NotificationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get( '/unauthenticated', [AuthController::class, 'unauthenticated'])->name('login');

Route::group(['prefix' => 'auth'], function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/user', [AuthController::class, 'getUser']);
        Route::post('/logout', [AuthController::class, 'logout']);
    });
});

Route::middleware(['auth:sanctum'])->group(function () {
    Route::group(['prefix' => 'items'], function () {
        Route::get('/', [ItemController::class, 'index']);
        Route::get('/{id}', [ItemController::class, 'show']);
        Route::get('/{id}/bill', [ItemController::class, 'getBill']);

        Route::middleware(['role:admin'])->group(function () {
            Route::post('/', [ItemController::class, 'store']);
            Route::put('/{id}', [ItemController::class, 'update']);
            Route::delete('/{id}', [ItemController::class, 'delete']);
        });
    });

    Route::group(['prefix' => 'bids'], function () {
        Route::get('/', [BidController::class, 'index']);
        Route::post('/', [BidController::class, 'store']);
        Route::post('/auto-bid', [BidController::class, 'storeAutoBid']);
        Route::delete('/auto-bid/{itemId}', [BidController::class, 'deleteAutoBid']);
    });

    Route::group(['prefix' => 'notifications'], function () {
        Route::get('/', [NotificationController::class, 'index']);
        Route::post('/read-all', [NotificationController::class, 'readAll']);
        Route::post('/{id}/read', [NotificationController::class, 'read']);
    });

    Route::group(['prefix' => 'overviews'], function () {
        Route::get('/', [\App\Http\Controllers\Api\OverviewController::class, 'index']);
    });
});
