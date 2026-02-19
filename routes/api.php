<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\SupplierController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\ReportController;

// Rutas públicas
Route::post('/login', [AuthController::class, 'login']);

// Rutas protegidas
Route::middleware('auth:sanctum')->group(function () {
    // Movimientos de stock
    Route::get('/stock-movements', [App\Http\Controllers\Api\StockMovementController::class, 'index']);
    Route::post('/stock-movements', [App\Http\Controllers\Api\StockMovementController::class, 'store']);
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);
    // Ventas
    Route::get('/sales', [App\Http\Controllers\Api\SaleController::class, 'index']);
    Route::post('/sales', [App\Http\Controllers\Api\SaleController::class, 'store']);
    Route::get('/sales/{sale}', [App\Http\Controllers\Api\SaleController::class, 'show']);
    Route::put('/sales/{sale}/cancel', [App\Http\Controllers\Api\SaleController::class, 'cancel']);

    Route::apiResource('categories', CategoryController::class);
    Route::apiResource('suppliers',  SupplierController::class);
    Route::apiResource('products',   ProductController::class);

    Route::prefix('reports')->group(function () {
        Route::get('/summary',     [ReportController::class, 'summary']);
        Route::get('/low-stock',   [ReportController::class, 'lowStock']);
        Route::get('/by-category', [ReportController::class, 'byCategory']);
    });
    // Usuarios
    Route::get('/users', [App\Http\Controllers\Api\UserController::class, 'index']);
    Route::post('/users', [App\Http\Controllers\Api\UserController::class, 'store']);
    Route::put('/users/{user}', [App\Http\Controllers\Api\UserController::class, 'update']);
    Route::put('/profile', [App\Http\Controllers\Api\AuthController::class, 'updateProfile']);
    Route::delete('/users/{user}', [App\Http\Controllers\Api\UserController::class, 'destroy']);
    Route::get('/roles', [App\Http\Controllers\Api\UserController::class, 'roles']);
});
