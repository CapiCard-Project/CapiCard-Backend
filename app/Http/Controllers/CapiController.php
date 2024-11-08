<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\CapibaraCard;
use App\Models\Cards_By_Users;
use Illuminate\Support\Facades\Auth;

use function Laravel\Prompts\error;

class CapiController
{
    public function getCapibaraCard() 
    {
        // validar si hay cartas de capibara
        $capi = CapibaraCard::where('image', '!=', '')->get();
        if($capi->isEmpty()){
            return response()->json([
                'message' => 'No se encontraron cartas de capibara'
            ], 404);
        }

        return response()->json([$capi], 200);

    }

    /**
     * Obtener una carta de capibara por su rareza
     * @param Request $request
     */
    public function OpenPack(Request $request)
    {
        $probability = $request->input('probability');
    
        if($probability === 1) {
            $probabilidades = [
                1 => 100,
                2 => 7,
                3 => 7,
                4 => 6,
                5 => 5
            ];
        } else if($probability === 2) {
            $probabilidades = [
                1 => 70,
                2 => 20,
                3 => 10,
                4 => 4,
                5 => 2
            ];
        } else if($probability === 3) {
            $probabilidades = [
                1 => 60,
                2 => 25,
                3 => 10,
                4 => 5,
                5 => 0
            ];
        } else if($probability === 4) {
            $probabilidades = [
                1 => 60,
                2 => 25,
                3 => 10,
                4 => 5,
                5 => 2
            ];
        } else if($probability === 5) {
            $probabilidades = [
                1 => 40,
                2 => 25,
                3 => 20,
                4 => 30,
                5 => 20
            ];
        }
    
        $capiCard = CapibaraCard::all();

        if($capiCard->isEmpty()){
            return response()->json([
                'message' => 'No se encontraron cartas de capibara'
            ], 404);
        }
        
        $sorteo = collect();

        foreach($capiCard as $card) {
            $rarity = $card->rarity;
            if(isset($probabilidades[$rarity])) {
                $sorteo = $sorteo->merge(array_fill(0, $probabilidades[$rarity], $card));
            }else {
                return response()->json([
                    'message' => 'No se encontraron cartas de capibara'
                ], 500);
            }

        }
        return response()->json([
            'status' => 200,
            'message' => 'Carta obtenida',
            'card' => $sorteo->random()
        ]);
    }

    /**
     * Guardar una carta de capibara
     * @param Request $request
     */
    public function saveCardByUser(Request $request)
    {
        $user = Auth::user();
        $cardId = $request->input('card_id');

        if ($cardId === null) {
            return response()->json([
                'message' => 'El id de la carta es requerido'
            ], 400);
        };

        $card = new Cards_By_Users;

        //insertar la carta en la base de datos
        $card->user_id = $user->id;
        $card->card_id = $request->input('card_id');
        $card->save();

        return response()->json([
            'status' => 200,
            'message' => 'Carta guardada'
        ]);
    }



}
