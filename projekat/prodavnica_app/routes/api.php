<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\Auth\ApiRegisterController;
use App\Http\Controllers\Auth\LogInController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/register', [ApiRegisterController::class, 'register']);
Route::post('login', [LogInController::class, 'login']);

Route::middleware('auth:api')->post('/proizvodi', [ProductController::class, 'store']);