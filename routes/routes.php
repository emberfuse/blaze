<?php

use Illuminate\Support\Facades\Route;
use Cratespace\Preflight\Http\Controllers\ApiTokenController;
use Cratespace\Preflight\Http\Controllers\UserProfilePhotoController;
use Cratespace\Preflight\Http\Controllers\OtherBrowserSessionsController;

Route::group([
    'prefix' => 'user',
    'middleware' => ['auth'],
], function (): void {
    Route::delete('/other-browser-sessions', [OtherBrowserSessionsController::class, '__invoke'])->name('other-browser-sessions.destroy');
    Route::delete('/profile-photo', [UserProfilePhotoController::class, '__invoke'])->name('current-user-photo.destroy');

    Route::get('/api-tokens', [ApiTokenController::class, 'index'])->name('api-tokens.index');
    Route::post('/api-tokens', [ApiTokenController::class, 'store'])->name('api-tokens.store');
    Route::put('/api-tokens/{token}', [ApiTokenController::class, 'update'])->name('api-tokens.update');
    Route::delete('/api-tokens/{token}', [ApiTokenController::class, 'destroy'])->name('api-tokens.destroy');
});
