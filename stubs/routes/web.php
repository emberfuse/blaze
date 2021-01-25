<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => Inertia::render('Marketing/Welcome'))->name('welcome');

Route::middleware(['auth:sanctum', 'verified'])->get(
    '/dashboard',
    fn () => Inertia::render('Dashboard')
)->name('dashboard');
