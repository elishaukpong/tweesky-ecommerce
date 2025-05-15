<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegistrationController;
use Illuminate\Support\Facades\Route;

Route::post('auth/register', RegistrationController::class)->name('auth.register');
Route::post('auth/login', LoginController::class)->name('auth.login');
