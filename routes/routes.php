<?php

use Illuminate\Support\Facades\Route;
use Cratespace\Citadel\Citadel\Config;
use Cratespace\Preflight\Http\Controllers\ApiTokenController;

Route::group(['middleware' => Config::middleware([['web']])], function (): void {
    Route::group([
        'prefix' => 'user',
        'middleware' => ['auth'],
    ], function (): void {
        /*
         * API Token Management Routes...
         */
        Route::get('/api-tokens', [ApiTokenController::class, 'index'])->name('api-tokens.index');
        Route::post('/api-tokens', [ApiTokenController::class, 'store'])->name('api-tokens.store');
        Route::put('/api-tokens/{token}', [ApiTokenController::class, 'update'])->name('api-tokens.update');
        Route::delete('/api-tokens/{token}', [ApiTokenController::class, 'destroy'])->name('api-tokens.destroy');
    });
});
