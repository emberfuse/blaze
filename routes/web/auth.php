<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;

Route::group([
    'middleware' => 'guest',
], function (): void {
    Route::get('/login', [LoginController::class, 'create'])->name('login.create');
    Route::post('/login', [LoginController::class, 'store'])->name('login.store');
});

Route::group([
    'middleware' => 'auth',
], function (): void {
    Route::post('/logout', [LoginController::class, 'destroy'])->name('login.destroy');
});
