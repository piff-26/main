<?php

namespace App\Services;

use App\Models\Transaction;
use Illuminate\Support\Facades\Http;

class MidtransService
{
    public static function getSnapToken(Transaction $transaction): string
    {
        $transaction->loadMissing('user', 'transactionItems.ticketCategory');
        $isProduction = env('MIDTRANS_IS_PRODUCTION', false);
        $serverKey    = env('MIDTRANS_SERVER_KEY');
        $baseUrl      = $isProduction
            ? 'https://app.midtrans.com/snap/v1/transactions'
            : 'https://app.sandbox.midtrans.com/snap/v1/transactions';

        $item_details = [];
        foreach ($transaction->transactionItems as $item) {
            $item_details[] = [
                'id'       => (string) $item->ticket_category_id,
                'price'    => (int) $item->price,
                'quantity' => (int) $item->quantity,
                'name'     => $item->ticketCategory->name,
            ];
        }

        if ($transaction->discount_amount > 0) {
            $item_details[] = [
                'id'       => 'VOUCHER',
                'price'    => -(int) $transaction->discount_amount,
                'quantity' => 1,
                'name'     => 'Diskon Voucher',
            ];
        }

        $payload = [
            'transaction_details' => [
                'order_id'     => $transaction->invoice_code,
                'gross_amount' => (int) $transaction->total_amount,
            ],
            'customer_details' => [
                'first_name' => $transaction->buyer_name,
                'phone'      => $transaction->buyer_phone,
                'email'      => $transaction->user->email,
            ],
            'item_details'     => $item_details,
            // 'enabled_payments' => ['qris'], // aktifkan saat hari-H
        ];

        $response = Http::withBasicAuth($serverKey, '')
            ->post($baseUrl, $payload);

        if ($response->failed()) {
            throw new \Exception('Midtrans error: ' . $response->body());
        }

        return $response->json('token');
    }
}
