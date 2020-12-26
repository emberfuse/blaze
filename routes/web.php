<?php

use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Route;

Route::get('{path}', fn (Request $request): View => view('app', compact('request')))->where('path', '(.*)');

Route::get('/testable', fn () => 'Hello');
