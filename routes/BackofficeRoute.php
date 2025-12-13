<?php

use App\Presentation\Backoffice\V1\Category\Controllers\CategoryController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->middleware(['auth:api', 'authorization'])->group(function () {

    // Category Routes
    Route::prefix('categories')->group(function () {
        Route::get('/', [CategoryController::class, 'index']);
        Route::get('/{id}', [CategoryController::class, 'show']);
        Route::post('/', [CategoryController::class, 'store']);
        Route::put('/{id}', [CategoryController::class, 'update']);
        Route::delete('/{id}', [CategoryController::class, 'destroy']);
    });
});
