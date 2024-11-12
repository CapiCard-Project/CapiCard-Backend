<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CapiController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MarketController;
use App\Models\CapibaraCard;
use Faker\Provider\ar_EG\Payment;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\TransactionController;

//rutas para la autenticacion de usuarios
Route::post('/register', [AuthController::class, 'create_user']);
Route::post('/login', [AuthController::class, 'login']);
//

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    //ruta de usuario
    Route::put('/update-coins', [AuthController::class, 'update_coins']);
    Route::post('/uploadImage', [AuthController::class, 'updated_image']);

    //rutas de pago
    Route::get('/bankList', [PaymentController::class, 'bankList']);
    Route::post('/createPayment', [PaymentController::class, 'createPayment']);
    Route::post('/saveTransactionByUser', [TransactionController::class, 'saveTransactionByUser']);

    //rutas para el manejo de cartas
    Route::post('/saveCardByUser', [CapiController::class, 'saveCardByUser']);
    Route::post('/capiOpenPack', [CapiController::class, 'OpenPack']);
    Route::get('/capibara-card', [CapiController::class, 'getCapibaraCard']);
    Route::get('/cardsByUser', [CapiController::class, 'getCardsByUser']);

    //rutas para el mercado
    Route::post('cards/sale', [MarketController::class, 'cards_for_sale']);
    Route::get('cards/sale', [MarketController::class, 'get_card_for_sale']);
    Route::post('cards/buy', [MarketController::class, 'buy_card']);

});

// url de retorno de mercado pago
Route::post('/webHookMercadoPago', [PaymentController::class, 'webHookMercadoPago']);

