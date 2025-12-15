<?php

use App\Presentation\Backoffice\V1\AuditLog\Controllers\AuditLogController;
use App\Presentation\Backoffice\V1\Category\Controllers\CategoryController;
use App\Presentation\Backoffice\V1\File\Controllers\FileController;
use App\Presentation\Backoffice\V1\Permission\Controllers\PermissionController;
use App\Presentation\Backoffice\V1\Product\Controllers\ProductController;
use App\Presentation\Backoffice\V1\Role\Controllers\RoleController;
use App\Presentation\Backoffice\V1\SalesOrder\Controllers\SalesOrderController;
use App\Presentation\Backoffice\V1\StockMovement\Controllers\StockMovementController;
use App\Presentation\Backoffice\V1\User\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// File Routes (No auth required)
Route::prefix('v1/files')->group(function () {
    Route::post('/upload', [FileController::class, 'upload']);
});

Route::prefix('v1')->middleware(['auth:api', 'authorization'])->group(function () {

    // Category Routes
    Route::prefix('categories')->group(function () {
        Route::get('/', [CategoryController::class, 'index']);
        Route::get('/{id}', [CategoryController::class, 'show']);
        Route::post('/', [CategoryController::class, 'store']);
        Route::put('/{id}', [CategoryController::class, 'update']);
        Route::delete('/{id}', [CategoryController::class, 'destroy']);
    });

    // Product Routes
    Route::prefix('products')->group(function () {
        Route::get('/', [ProductController::class, 'index']);
        Route::get('/{id}', [ProductController::class, 'show']);
        Route::post('/', [ProductController::class, 'store']);
        Route::put('/{id}', [ProductController::class, 'update']);
        Route::delete('/{id}', [ProductController::class, 'destroy']);
    });

    // Audit Log Routes
    Route::prefix('audit-logs')->group(function () {
        Route::get('/', [AuditLogController::class, 'index']);
    });

    // Stock Movement Routes
    Route::prefix('stock-movements')->group(function () {
        Route::get('/', [StockMovementController::class, 'index']);
        Route::get('/{id}', [StockMovementController::class, 'show']);
        Route::post('/', [StockMovementController::class, 'store']);
    });

    // Sales Order Routes
    Route::prefix('sales-orders')->group(function () {
        Route::get('/', [SalesOrderController::class, 'index']);
        Route::get('/{id}', [SalesOrderController::class, 'show']);
        Route::post('/', [SalesOrderController::class, 'store']);
        Route::put('/{id}', [SalesOrderController::class, 'update']);
    });

    // User Routes
    Route::prefix('users')->group(function () {
        Route::get('/', [UserController::class, 'index']);
        Route::get('/{id}', [UserController::class, 'show']);
        Route::post('/', [UserController::class, 'store']);
        Route::put('/{id}', [UserController::class, 'update']);
        Route::delete('/{id}', [UserController::class, 'destroy']);
    });

    // Role Routes
    Route::prefix('roles')->group(function () {
        Route::get('/', [RoleController::class, 'index']);
        Route::get('/{id}', [RoleController::class, 'show']);
        Route::post('/', [RoleController::class, 'store']);
        Route::put('/{id}', [RoleController::class, 'update']);
        Route::delete('/{id}', [RoleController::class, 'destroy']);
    });

    // Permission Routes
    Route::prefix('permissions')->group(function () {
        Route::get('/', [PermissionController::class, 'index']);
        Route::get('/{id}', [PermissionController::class, 'show']);
        Route::post('/', [PermissionController::class, 'store']);
        Route::put('/{id}', [PermissionController::class, 'update']);
        Route::delete('/{id}', [PermissionController::class, 'destroy']);
    });
});
