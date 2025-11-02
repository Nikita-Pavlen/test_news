<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function () {
    Auth::routes();
});

Route::get('{page}', function () {
    return view('main');
})
    ->where('page', '.*');
