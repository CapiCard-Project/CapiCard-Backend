<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PaymentController
{
    /**
     * Obtiene la lista de bancos para PSE
     */
    public function bankList()
    {
        try {

            // obtenemos la respues de mercado pago
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . env('MERCADO_PAGO_ACCESS_TOKEN'),
                'Content-Type' => 'application/json'
            ])->get('https://api.mercadopago.com/v1/payment_methods');
            
            Log::info('Respuesta de la lista de bancos: ' . $response->body());

            // validamos la respusta
            if ( $response->successful()) {
                $pseData = $response->json();

                $pse = collect($pseData)->firstWhere('id', 'pse');

                $financial_institutions = $pse['financial_institutions'] ?? [];

                $data = [
                    'id' => $pse['id'],
                    'name' => $pse['name'],
                    'financial_institutions' => $financial_institutions
                ];

                return response()->json($data);
            }

        } catch (\Exception $e) {
            Log::error('Error al obtener la lista de bancos: ' . $e->getMessage());
            return response()->json([
                'message' => 'Error al obtener la lista de bancos',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Registrar el pago con pse consumiendo el endpoint de mercado pago
     * @param Request $request
     */
    public function createPayment(Request $request)
    {
        try {

            $data = json_decode($request->getContent(), true);
            Log::info('Datos de la transacción: ' . json_encode($data));
            $paymntData = [
                'external_reference' => uniqid(),
                'transaction_amount' => $data['amount'],
                'payment_method_id' => 'pse',
                'transaction_details' => [
                    'financial_institution' => $data['financial_institution']
                ],
                'payer' => [
                    'email' => $data['payer']['email'],
                    'entity_type' => 'individual',
                    'identification' => [
                        'type' => $data['payer']['identification']['type'],
                        'number' => $data['payer']['identification']['number']
                    ],
                    'first_name' => $data['payer']['first_name'],
                    'last_name' => $data['payer']['last_name'],
                    'phone' => [
                        'number' => $data['payer']['phone']
                    ],
                ],
                'additional_info' => [
                        'ip_address' => request()->ip()
                ],
                "callback_url" => 'https://api.capi.shop/api/webHookMercadoPago',
                "notification_url" => 'https://api.capi.shop/api/webHookMercadoPago'
            ];

            $response = Http::withHeaders([
                'Authorization' => 'Bearer '. env('MERCADO_PAGO_ACCESS_TOKEN'),
                'Content-Type' => 'application/json',
                'X-Idempotency-Key' => uniqid()
            ])->post('https://api.mercadopago.com/v1/payments', $paymntData);

            if ($response->successful()) {
                Log::info($response->json());
            }else {
                Log::info($response->json());
            }

        } catch (\Exception $e) {
            Log::error('Error al registrar el pago con PSE: ' . $e->getMessage());
            return response()->json([
                'message' => 'Error al registrar el pago con PSE',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Webhook de mercado pago
     * @param Request $request
     */
    public function webHookMercadoPago(Request $request)
    {
        $data = $request->all();
        Log::info('webHookMercadoPago: ' . json_encode($data));
    }
}
