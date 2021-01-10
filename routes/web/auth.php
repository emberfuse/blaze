<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\UserProfileController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPassswordController;
use App\Http\Controllers\Auth\ConfirmPasswordController;
use App\Http\Controllers\Auth\UserProfilePhotoController;
use App\Http\Controllers\Auth\ConfirmedPasswordStatusController;

Route::group([
    'middleware' => 'guest',
], function (): void {
    /*
     * Login Routes...
     */
    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store']);

    /*
     * Register Routes...
     */
    Route::get('/register', [RegisterController::class, 'create'])->name('register');
    Route::post('/register', [RegisterController::class, 'store']);

    /*
     * Reset Password Routes...
     */
    Route::get('/forgot-password', [ForgotPasswordController::class, 'create'])->name('password.request');
    Route::get('/reset-password/{token}', [ResetPassswordController::class, 'create'])->name('password.reset');
    Route::post('/forgot-password', [ForgotPasswordController::class, 'store'])->name('password.email');
    Route::post('/reset-password', [ResetPassswordController::class, 'store'])->name('password.update');
});

Route::group([
    'middleware' => 'auth',
], function (): void {
    /*
     * Logout Routes...
     */
    Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');

    /*
     * Authenticated Routes...
     */
    Route::group(['prefix' => 'user'], function (): void {
        /*
         * User Profile Routes...
         */
        Route::get('/profile', [UserProfileController::class, 'show'])->name('user.show');
        Route::put('/profile', [UserProfileController::class, 'update'])->name('user.update');
        Route::delete('/profile', [UserProfileController::class, 'destroy'])->name('user.destroy');
        Route::delete('/profile-photo', [UserProfilePhotoController::class, 'destroy'])->name('user-photo.destroy');

        /*
         * User Password Routes...
         */
        Route::put('/password', [PasswordController::class, 'update'])->name('user-password.update');

        /*
         * User Password Confirmation Routes...
         */
        Route::get('/confirmed-password-status', [ConfirmedPasswordStatusController::class, '__invoke'])->name('password.confirmation');
        Route::post('/confirm-password', [ConfirmPasswordController::class, '__invoke']);
    });
});
