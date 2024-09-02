<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CapiController;
use App\Http\Controllers\AuthController;



//rutas para la autenticacion de usuarios
Route::post('/register', [AuthController::class, 'create_user']);
Route::post('/login', [AuthController::class, 'login']);
//

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::get('/capibara-card', [CapiController::class, 'getCapibaraCard']);


//ruta de usuario
Route::get('/user', [AuthController::class, 'user']);

