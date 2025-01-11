<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\Auth\ApiRegisterController;
use App\Http\Controllers\Auth\LogInController;
use App\Http\Middleware\IsAdmin;
use App\Http\Controllers\ReceptController;
use App\Http\Controllers\KorpaController;

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

//Recept
Route::middleware(['auth:sanctum', IsAdmin::class])->post('recepti', [ReceptController::class, 'store']);
Route::middleware(['auth:sanctum', IsAdmin::class])->patch('/recepti/{id}', [ReceptController::class, 'update']);
Route::get('/recepti/pretraga', [ReceptController::class, 'pretraga']);

//Korpa
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/korpa/add-product', [KorpaController::class, 'addProduct']);
    Route::post('/korpa/remove-product', [KorpaController::class, 'removeProduct']);
    Route::get('/korpa', [KorpaController::class, 'viewCart']);
});

