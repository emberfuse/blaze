<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Business\HomeController;

Route::group([
    'middleware' => 'auth',
], function (): void {
    Route::get('/home', [HomeController::class, '__invoke'])->name('home');
});
