<?php


use App\Http\Controllers\API\CarController;
use App\Http\Controllers\API\ClientController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/clients', [ClientController::class, 'index']);
Route::get('/clients/{client}/cars', [ClientController::class, 'cars']);
Route::get('/car/{car}/services', [CarController::class, 'services']);
Route::get('/search-clients', [ClientController::class, 'search']);