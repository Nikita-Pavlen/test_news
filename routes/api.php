<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(['web'])->group(function () {
    Auth::routes();
});

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

