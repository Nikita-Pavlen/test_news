<?php

use App\Http\Controllers\LikeController;
use App\Http\Controllers\NewsController;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::post('/news/{news}/likes', [LikeController::class, 'store']);
    Route::delete('/news/{news}/likes', [LikeController::class, 'destroy']);
    Route::resource('news', NewsController::class);
});

Route::get('/roles', function () {
    return Role::all(['id', 'name']);
});
