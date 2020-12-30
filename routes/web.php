<?php

use Illuminate\Support\Facades\Route;

Route::get('/', fn () => inertia('Marketing/Welcome'));
