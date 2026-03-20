<?php

namespace App\Services;

use App\Models\Transaction;
use Midtrans\Config;
use Midtrans\Snap;

class MidtransService
{
    public static function getSnapToken(Transaction $transaction): string
    {
        Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        Config::$isProduction = env('MIDTRANS_IS_PRODUCTION', false);
        Config::$isSanitized = true;
        Config::$is3ds = true;

        $item_details = [];
        foreach ($transaction->transactionItems as $item) {
            $item_details[] = [
                'id'       => $item->ticket_category_id,
                'price'    => $item->price,
                'quantity' => $item->quantity,
                'name'     => $item->ticketCategory->name,
            ];
        }

        if ($transaction->discount_amount > 0) {
            $item_details[] = [
                'id'       => 'VOUCHER',
                'price'    => -$transaction->discount_amount,
                'quantity' => 1,
                'name'     => 'Diskon Voucher',
            ];
        }

        $params = [
            'transaction_details' => [
                'order_id'     => $transaction->invoice_code,
                'gross_amount' => $transaction->total_amount,
            ],
            'customer_details' => [
                'first_name' => $transaction->buyer_name,
                'phone'      => $transaction->buyer_phone,
            ],
            'item_details'     => $item_details,
            // 'enabled_payments' => ['qris','other_qris'], note: hari-h nanti nyalain ini aja
            'enabled_payments' => ['gopay','bca'],
        ];

        return Snap::getSnapToken($params);
    }
}
