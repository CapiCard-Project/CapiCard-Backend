<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\CardMarket;
use App\Models\Cards_By_Users;
use App\Models\User;
use App\Models\CardTransactions;

class MarketController
{
    /**
     * Recibe los parametros de la carta que el usuario quiere vender
     * @param Request $request
     */
    public function cards_for_sale(Request $request)
    {
        // obtenemos el usuario autenticado
        $user = Auth::user();

        $validated = Validator::make($request->all(), [
            'card_id' => 'required',
            'price' => 'required',
            'time' => 'required'
        ]);

        // validar los campos
        if ($validated->fails()) {
            return response()->json([
                'message' => 'Error al validar los campos',
                'errors' => $validated->errors()
            ], 400);
        }

        $cardByUser = Cards_By_Users::where('user_id', $user->id)
            ->where('card_id', $request->input('card_id'))
            ->first();

        $cardByUser->quantity = $cardByUser->quantity - 1;
        $cardByUser->save();

        $card = new CardMarket;

        $card->user_id = $user->id;
        $card->card_id = $request->input('card_id');
        $card->price = $request->input('price');
        $card->time = $request->input('time');

        $card->save();

        return response()->json([
            'status' => 200,
            'message' => 'Carta publicada'
        ]);
    }

    /**
     * Obtiene las cartas publicadas por los usuarios
     */
    public function get_card_for_sale()
    {
        $user = Auth::user();

        $cardsForSale = CardMarket::where('is_active', true)
            ->where('user_id', '!=', $user->id)
            ->with('card:id,nameCard,description,image,state,rarity')
            ->get()
            ->map(function ($cardMarket) {
                return [
                    'id_card' => $cardMarket->card->id,
                    'id_user' => $cardMarket->user_id,
                    'id_market' => $cardMarket->id,
                    'image' => $cardMarket->card->image,
                    'name' => $cardMarket->card->nameCard,
                    'description' => $cardMarket->card->description,
                    'rarity' => $cardMarket->card->rarity,
                    'price' => $cardMarket->price,
                    'time' => $cardMarket->time,
                ];
            });
        return response()->json([
            'status' => 200,
            'cards' => $cardsForSale
        ]);
    }

    /**
     * Registra la compra de una carta
     * @param Request $reuest
     */
    public function buy_card(Request $request)
    {
        $user = Auth::user(); 

        $validated = Validator::make($request->all(), [
            'id_market' => 'required',
            'id_user' => 'required',
            'id_card' => 'required',
            'price' => 'required'
        ]);

        if ($validated->fails()) {
            return response()->json([
                'message' => 'Error al validar los campos',
                'errors' => $validated->errors()
            ], 400);
        }

        // buscar la carta en el mercado
        $cardMarket = CardMarket::where('id', $request->input('id_market'))
            ->where('is_active', true)
            ->first();
        // pasamos el estado de la carta a vendida
        $cardMarket->is_active = false;
        $cardMarket->save();

        // operamos los capipoints del usuario
        $user->capipoins = $user->capipoins - $request->input('price');
        $user->save();

        // guardamos los capipoints del vendedor
        $userSeller = User::where('id', $request->input('id_user'))->first();
        $userSeller->capipoins = $userSeller->capipoins + $request->input('price');
        $userSeller->save();

        // restamos la cantidad de cartas del usuario
        $cardByUser = Cards_By_Users::where('user_id', $request->input('id_user'))
            ->where('card_id', $request->input('id_card'))
            ->first();
        
        $cardByUser->quantity = $cardByUser->quantity - 1;
        $cardByUser->save();

        if ($cardByUser->quantity == 0 || $cardByUser->quantity < 0) {
            $cardByUser->delete();
        }

        $newCardByUser = new Cards_By_Users;
        $newCardByUser->user_id = $user->id;
        $newCardByUser->card_id = $request->input('id_card');
        $newCardByUser->save();

        // guardamos la transaccion de la carta
        $card = new CardTransactions;
        $card->buyer_id = $userSeller->id;
        $card->seller_id = $user->id;
        $card->card_id = $request->input('id_card');
        $card->price = $request->input('price');
        $card->save();

        return response()->json([
            'status' => 200,
            'message' => 'Carta comprada'
        ]);
    }
}
