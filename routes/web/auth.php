<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\UserProfileController;
use App\Http\Controllers\Auth\RecoveryCodeController;
use App\Http\Controllers\Auth\AuthenticationController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPassswordController;
use App\Http\Controllers\Auth\ConfirmPasswordController;
use App\Http\Controllers\Auth\UserProfilePhotoController;
use App\Http\Controllers\Auth\OtherBrowserSessionsController;
use App\Http\Controllers\Auth\ConfirmedPasswordStatusController;
use App\Http\Controllers\Auth\TwoFactorAuthenticationController;

Route::group([
    'middleware' => 'guest',
], function (): void {
    $limiter = config('auth.limiters.login');

    /*
     * Login Routes...
     */
    Route::get('/login', [AuthenticationController::class, 'create'])
        ->middleware(array_filter([
            'guest', $limiter ? 'throttle:' . $limiter : null,
        ]))
        ->name('login');
    Route::post('/login', [AuthenticationController::class, 'store']);

    /*
     * Two Factor Authentication Challenge Route...
     */
    Route::get('/two-factor-challenge', [TwoFactorAuthenticationController::class, 'create'])->name('two-factor.login');

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
    Route::post('/logout', [AuthenticationController::class, 'destroy'])->name('logout');

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
        Route::post('/confirm-password', [ConfirmPasswordController::class, '__invoke'])->name('password.confirm');

        /*
         * Logout Other Browser Sessions Routes...
         */
        Route::delete('/other-browser-sessions', [OtherBrowserSessionsController::class, 'destroy'])->name('other-browser-sessions.destroy');

        /*
         * Two Factro Authentication Routes...
         */
        $twoFactorLimiter = config('auth.limiters.two-factor');

        Route::post('/two-factor-challenge', [TwoFactorAuthenticatedSessionController::class, 'store'])
            ->middleware(array_filter([
                'guest', $twoFactorLimiter ? 'throttle:' . $twoFactorLimiter : null,
            ]));

        Route::group([
            'middleware' => ['auth', 'password.confirm'],
        ], function () {
            Route::post('/two-factor-authentication', [TwoFactorAuthenticationController::class, 'store']);
            Route::delete('/two-factor-authentication', [TwoFactorAuthenticationController::class, 'destroy']);
            Route::get('/two-factor-qr-code', [TwoFactorQrCodeController::class, 'show']);
            Route::get('/two-factor-recovery-codes', [RecoveryCodeController::class, 'index']);
            Route::post('/two-factor-recovery-codes', [RecoveryCodeController::class, 'store']);
        });
    });
});
