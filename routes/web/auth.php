<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\UserProfileController;
use App\Http\Controllers\Auth\ResetPassswordController;
use App\Http\Controllers\Auth\UserProfilePhotoController;
use App\Http\Controllers\Auth\ResetPassswordLinkController;

Route::group([
    'middleware' => 'guest',
], function (): void {
    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store']);

    Route::get('/register', [RegisterController::class, 'create'])->name('register');
    Route::post('/register', [RegisterController::class, 'store']);

    Route::get('/forgot-password', [ResetPassswordLinkController::class, 'create'])->name('password.request');
    Route::get('/reset-password/{token}', [ResetPassswordController::class, 'create'])->name('password.reset');
    Route::post('/forgot-password', [ResetPassswordLinkController::class, 'store'])->name('password.email');
    Route::post('/reset-password', [ResetPassswordController::class, 'store'])->name('password.update');
});

Route::group([
    'middleware' => 'auth',
], function (): void {
    Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');

    Route::get('/user/profile', [UserProfileController::class, 'show'])->name('user.show');
    Route::put('/user/profile', [UserProfileController::class, 'update'])->name('user.update');
    Route::delete('/user/profile', [UserProfileController::class, 'destroy'])->name('user.destroy');
    Route::delete('/user/profile-photo', [UserProfilePhotoController::class, 'destroy'])->name('user-photo.destroy');
});
