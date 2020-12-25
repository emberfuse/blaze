<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionsController;

Route::group(['middleware' => 'guest'], function (): void {
    Route::post('/login', [AuthenticatedSessionsController::class, '__invoke'])->name('login');
});
