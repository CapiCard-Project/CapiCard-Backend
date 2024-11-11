<?php

namespace App\Http\Controllers;

use App\Models\TransactionsDetails;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\TransacionsByUser;

class TransactionController
{
    /**
     * Recibe el id de la transaccion desde el cliente y la relaciona con el usuario
     * @param Request $request
     */
    public function saveTransactionByUser(Request $request)
    {
        // obtenemos el usuario autenticado
        $user = Auth::user();

        // validar el id de la transaccion
        $validated = Validator::make($request->all(), [
            'transaction_id' => 'required'
        ]);

        if ($validated->fails()) {
            return response()->json([
                'message' => 'Error al validar la transaccion',
                'errors' => $validated->errors()
            ], 400);
        }

        $transaction_id = $request->input('transaction_id');

        // obtener la informacion de la transaccion
        $transaction = TransactionsDetails::find($transaction_id);

        // relacionamos la transaccion con el usuario
        $transaction_by_user = new TransacionsByUser;
        $transaction_by_user->user_id = $user->id;
        $transaction_by_user->transaction_id = $transaction->id;
        $transaction_by_user->save();

        if ($transaction->status == 'approved') {
            $user->capipoins = $user->capipoins + 500;
        } else {
            return response()->json([
                'status' => 'rejected',
                'message' => 'La transaccion fue rechazada',
                'capipoins' => $user->capipoins
            ]);
        }

        return response()->json([
            'status' => 'approved',
            'message' => 'La transaccion fue aprobada',
            'capipoins' => $user->capipoins
        ]);
    }
}
