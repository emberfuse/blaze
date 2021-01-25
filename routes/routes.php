<?php

use Illuminate\Support\Facades\Route;
use Cratespace\Preflight\Http\Controllers\ApiTokenController;
use Cratespace\Preflight\Http\Controllers\UserProfilePhotoController;
use Cratespace\Preflight\Http\Controllers\OtherBrowserSessionsController;

Route::delete('/user/other-browser-sessions', [OtherBrowserSessionsController::class, '__invoke'])->name('other-browser-sessions.destroy');
Route::delete('/user/profile-photo', [UserProfilePhotoController::class, '__invoke'])->name('current-user-photo.destroy');

Route::get('/user/api-tokens', [ApiTokenController::class, 'index'])->name('api-tokens.index');
Route::post('/user/api-tokens', [ApiTokenController::class, 'store'])->name('api-tokens.store');
Route::put('/user/api-tokens/{token}', [ApiTokenController::class, 'update'])->name('api-tokens.update');
Route::delete('/user/api-tokens/{token}', [ApiTokenController::class, 'destroy'])->name('api-tokens.destroy');
