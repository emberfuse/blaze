<?php

use Illuminate\Support\Facades\Route;

Route::get('/', fn () => inertia('Marketing/Welcome'))->name('welcome');

/**
 * Auth Routes...
 */
require __DIR__ . '/web/auth.php';

/**
 * Business Routes...
 */
require __DIR__ . '/web/business.php';
