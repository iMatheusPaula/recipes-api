<?php

use App\Http\Controllers\RecipeController;
use App\Http\Controllers\ReviewController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;


Route::controller(AuthController::class)->group(function () {
    Route::post('/register', 'register');
    Route::post('/login', 'login');
    Route::post('/logout', 'logout')->middleware('auth:sanctum');
});

Route::prefix('recipes')->group(function () {
    // Public routes
    Route::controller(RecipeController::class)->group(function () {
        Route::get('/', 'index');
        Route::get('/{recipe}', 'show');
    });

    // Review routes
    Route::post('/{recipe}/reviews', [ReviewController::class, 'store']);

    // Protected routes
    Route::controller(RecipeController::class)->middleware('auth:sanctum')->group(function () {
        Route::post('/', 'store');
        Route::put('/{recipe}', 'update')->middleware('can:update,recipe');
        Route::delete('/{recipe}', 'destroy')->middleware('can:delete,recipe');
    });
});
