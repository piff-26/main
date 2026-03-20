<?php

namespace App\Http\Controllers;

use App\Enums\TransactionStatusEnum;
use App\Models\Transaction;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    public function callback(Request $request)
    {
        $serverKey = env('MIDTRANS_SERVER_KEY');

        // Validasi signature key
        $hashed = hash('sha512',
            $request->order_id .
            $request->status_code .
            $request->gross_amount .
            $serverKey
        );

        if ($hashed !== $request->signature_key) {
            return response()->json(['message' => 'Invalid signature'], 403);
        }

        $transaction = Transaction::with('transactionItems.ticketCategory')
            ->where('invoice_code', $request->order_id)
            ->first();

        if (!$transaction) {
            return response()->json(['message' => 'Transaction not found'], 404);
        }

        // Abaikan jika sudah paid
        if ($transaction->transaction_status === TransactionStatusEnum::PAID->value) {
            return response()->json(['message' => 'Already paid'], 200);
        }

        $status      = $request->transaction_status;
        $fraudStatus = $request->fraud_status;

        if (in_array($status, ['capture', 'settlement'])) {
            if ($fraudStatus === 'challenge') {
                $transaction->update(['transaction_status' => TransactionStatusEnum::PENDING->value]);
            } else {
                $transaction->update([
                    'transaction_status' => TransactionStatusEnum::PAID->value,
                    'payment_method'     => $request->payment_type,
                    'payment_reference'  => $request->transaction_id,
                    'paid_at'            => now(),
                ]);

                // Generate tiket jika belum ada
                if ($transaction->tickets()->count() === 0) {
                    foreach ($transaction->transactionItems as $item) {
                        for ($i = 0; $i < $item->quantity; $i++) {
                            $ticket = Ticket::create([
                                'transaction_id'     => $transaction->id,
                                'ticket_category_id' => $item->ticket_category_id,
                                'ticket_code'        => 'TEMP-' . Str::random(10),
                            ]);

                            $categorySlug  = strtoupper($item->ticketCategory->slug);
                            $invRandom     = substr($transaction->invoice_code, 4);
                            $newTicketCode = "INV-{$categorySlug}-{$invRandom}-" . strtoupper(Str::random(3));

                            $ticket->update(['ticket_code' => $newTicketCode]);
                        }
                    }
                }
            }
        } elseif (in_array($status, ['cancel', 'deny', 'expire'])) {
            $transaction->update([
                'transaction_status' => TransactionStatusEnum::EXPIRED->value,
                'cancel_reason'      => 'Midtrans status: ' . $status,
            ]);

            foreach ($transaction->transactionItems as $item) {
                $item->ticketCategory->decrement('sold_count', $item->quantity);
            }
        } elseif ($status === 'pending') {
            $transaction->update(['transaction_status' => TransactionStatusEnum::PENDING->value]);
        }

        return response()->json(['message' => 'OK'], 200);
    }
}
