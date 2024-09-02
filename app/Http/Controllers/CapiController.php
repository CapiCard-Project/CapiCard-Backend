<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\CapibaraCard;

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


}
