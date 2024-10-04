<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CapiController;
use App\Http\Controllers\AuthController;
use App\Models\CapibaraCard;

//rutas para la autenticacion de usuarios
Route::post('/register', [AuthController::class, 'create_user']);
Route::post('/login', [AuthController::class, 'login']);
//



Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::get('/capibara-card', [CapiController::class, 'getCapibaraCard']);
    //ruta de usuario
    Route::post('/capiOpenPack', [CapiController::class, 'OpenPack']);
    Route::put('/update-coins', [AuthController::class, 'update_coins']);
    
});

