<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\CurrentUserController;

Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::get('/user', [CurrentUserController::class, '__invoke']);
});
