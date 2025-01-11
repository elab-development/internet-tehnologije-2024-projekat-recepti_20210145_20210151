<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KupovinaController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\Auth\ApiRegisterController;
use App\Http\Controllers\Auth\LogInController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Middleware\IsAdmin;
use App\Http\Controllers\ReceptController;
use App\Http\Controllers\KorpaController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

//Registracija i prijava
Route::post('/register', [ApiRegisterController::class, 'register']);
Route::post('/login', [LogInController::class, 'login']);
Route::post('/logout', [LogInController::class, 'logout'])->middleware('auth:sanctum');
//Resetovanhje lozinke
Route::post('/forgot-password', [ResetPasswordController::class, 'sendResetLinkEmail']);
Route::post('/reset-password', [ResetPasswordController::class, 'reset']);

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

//Kupovina
Route::middleware('auth:sanctum')->group(function () {
    // Prikazivanje svih kupovina
    Route::get('/kupovina', [KupovinaController::class, 'index']);

    // Kreiranje nove kupovine
    Route::post('/kupovina/store', [KupovinaController::class, 'store']);

    // Prikazivanje jedne kupovine
    Route::get('/kupovina/{id}', [KupovinaController::class, 'show']);

    // Ažuriranje kupovine
    Route::patch('/kupovina/{id}', [KupovinaController::class, 'update']);

    // Brisanje kupovine
    Route::delete('/kupovina/{id}', [KupovinaController::class, 'destroy']);
});
