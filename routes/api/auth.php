<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionsController;

Route::group(['middleware' => 'guest:api'], function (): void {
    Route::post('/login', [AuthenticatedSessionsController::class, 'store'])->name('login');
});

Route::group(['middleware' => 'auth:sanctum'], function (): void {
    Route::post('/logout', [AuthenticatedSessionsController::class, 'destroy'])->name('logout');
});
