<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\UserProfileController;
use App\Http\Controllers\Auth\UserProfilePhotoController;

Route::group([
    'middleware' => 'guest',
], function (): void {
    Route::get('/login', [LoginController::class, 'create'])->name('login.create');
    Route::post('/login', [LoginController::class, 'store'])->name('login.store');

    Route::get('/register', [RegisterController::class, 'create'])->name('register.create');
    Route::post('/register', [RegisterController::class, 'store'])->name('register.store');
});

Route::group([
    'middleware' => 'auth',
], function (): void {
    Route::post('/logout', [LoginController::class, 'destroy'])->name('login.destroy');

    Route::get('/user/profile', [UserProfileController::class, 'show'])->name('user.show');
    Route::put('/user/profile', [UserProfileController::class, 'update'])->name('user.update');
    Route::delete('/user/profile', [UserProfileController::class, 'destroy'])->name('user.destroy');
    Route::delete('/user/profile-photo', [UserProfilePhotoController::class, 'destroy'])->name('user-photo.destroy');
});
