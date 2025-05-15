<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegistrationController;
use App\Http\Controllers\V1\ProductController;
use App\Http\Controllers\V1\WishlistController;
use Illuminate\Support\Facades\Route;

Route::post('auth/register', RegistrationController::class)->name('auth.register');
Route::post('auth/login', LoginController::class)->name('auth.login');


Route::middleware(['auth:sanctum'])
    ->prefix('v1')
    ->group(function(){
        Route::apiResource('products', ProductController::class)->except(['create', 'edit']);
        Route::apiResource('wishlists', WishlistController::class)->except(['create', 'edit']);
    });
