<?php

use App\Http\Controllers\RecipeController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;


Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

Route::prefix('recipes')->controller(RecipeController::class)->group(function () {
    Route::get('/', 'index');
    Route::get('/{recipe}', 'show');

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/', 'store');
        Route::put('/{recipe}', 'update')->middleware('can:update,recipe');
        Route::delete('/{recipe}', 'destroy')->middleware('can:delete,recipe');
    });
});
