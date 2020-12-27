<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\CurrentUserController;

require __DIR__ . '/api/auth.php';

Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::get('/user', [CurrentUserController::class, '__invoke'])->name('current.user');
});
