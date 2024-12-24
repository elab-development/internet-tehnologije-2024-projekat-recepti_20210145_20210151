<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\ApiRegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ProizvodController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/register', [ApiRegisterController::class, 'register']);
Route::post('login', [LoginController::class, 'login']);

// proveri da li je ulogovan administrator, da li ima token
Route::middleware('auth:sanctum')->post('/proizvodi', [ProizvodController::class, 'store']); //post zahtev - saljemo podatke kroz body

Route::get('/proizvodi/pretraga', [ProizvodController::class, 'search']); //get zahtev - podaci u URL

Route::patch('/proizvodi/{id}', [ProizvodController::class, 'update']); //patch zahtev - delimicno azuriranje
