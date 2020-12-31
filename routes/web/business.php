<?php

use Illuminate\Support\Facades\Route;

Route::group([
    'middleware' => 'auth',
], function (): void {
    Route::get('/home', function () {
        auth()->logout();
    })->name('home');
});
