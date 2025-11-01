<?php

use Illuminate\Support\Facades\Route;

Route::get('{page}', function () {
    return view('main');
})
    ->where('page', '.*');
