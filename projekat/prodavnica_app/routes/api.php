<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\Auth\ApiRegisterController;
use App\Http\Controllers\Auth\LogInController;
use App\Http\Middleware\IsAdmin;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/register', [ApiRegisterController::class, 'register']);
Route::post('/login', [LogInController::class, 'login']);

//Proizvod
Route::middleware('auth:api')->post('/proizvodi', [ProductController::class, 'store']);
Route::get('/proizvodi/pretraga', [ProductController::class, 'search']); //get zahtev - podaci u URL
//Prvo proveri da li je korisnik administrator, ako jeste moze da menja proizvod
Route::middleware(['auth:sanctum', IsAdmin::class])->patch('/proizvodi/{id}', [ProductController::class, 'update']);