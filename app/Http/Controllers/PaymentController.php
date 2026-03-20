<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Midtrans\Config;
use Midtrans\Snap;
use App\Models\Ticket;
use Illuminate\Support\Str;
use App\Enums\TransactionStatusEnum;

class PaymentController extends Controller
{
    public function show($invoice_code)
    {
        // Transaksi berdasarkan invoice
        $transaction = Transaction::with('transactionItems.ticketCategory')
            ->where('invoice_code', $invoice_code)
            ->where('transaction_status', TransactionStatusEnum::PENDING->value)
            ->firstOrFail();

        // Jika sudah punya snap_token, langsung tampilkan view
        if ($transaction->snap_token) {
            return view('user.checkout.payment', [
                'transaction' => $transaction,
                'snapToken' => $transaction->snap_token
            ]);
        }

        // Konfigurasi Midtrans
        Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        Config::$isProduction = env('MIDTRANS_IS_PRODUCTION', false);
        Config::$isSanitized = true;
        Config::$is3ds = true;

        // Rincian Item (Item Details)
        $item_details = [];
        foreach ($transaction->transactionItems as $item) {
            $item_details[] = [
                'id'       => $item->ticket_category_id,
                'price'    => $item->price,
                'quantity' => $item->quantity,
                'name'     => $item->ticketCategory->name,
            ];
        }

        // Jika ada diskon voucher, tambahkan sebagai item dengan harga minum
        if ($transaction->discount_amount > 0) {
            $item_details[] = [
                'id'       => 'VOUCHER-' . $transaction->voucher_id,
                'price'    => -$transaction->discount_amount, // hapus mINUS
                'quantity' => 1,
                'name'     => 'Diskon Voucher',
            ];
        }

        // Susun Payload untuk dikirim ke Midtrans
        $params = [
            'transaction_details' => [
                'order_id'     => $transaction->invoice_code,
                'gross_amount' => $transaction->total_amount,
            ],
            'customer_details' => [
                'first_name' => $transaction->buyer_name,
                'phone'      => $transaction->buyer_phone,
            ],
            'item_details' => $item_details,
            'enabled_payments' => ['qris', 'gopay', 'shopeepay'],
        ];

        try {
            // Dapatkan Snap Token dari Midtrans
            $snapToken = Snap::getSnapToken($params);

            // Simpan token ke database agar tidak perlu request ulang jika user refresh halaman
            $transaction->update(['snap_token' => $snapToken]);

            return view('user.checkout.payment', [
                'transaction' => $transaction,
                'snapToken' => $snapToken
            ]);

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memuat Payment Gateway: ' . $e->getMessage());
        }
    }
    /**
     * Webhook / Callback untuk menerima notifikasi dari Midtrans
     */
    public function callback(Request $request)
    {
        $serverKey = env('MIDTRANS_SERVER_KEY');

        //Validasi Signature Key
        $hashed = hash("sha512", $request->order_id . $request->status_code . $request->gross_amount . $serverKey);
        
        if ($hashed !== $request->signature_key) {
            return response()->json(['message' => 'Invalid signature'], 403);
        }

        // Transaksi berdasarkan invoice_code
        $transaction = Transaction::with('transactionItems.ticketCategory')->where('invoice_code', $request->order_id)->first();

        if (!$transaction) {
            return response()->json(['message' => 'Transaction not found'], 404);
        }

        // Jika transaksi sudah paid sebelumnya, abaikan
        if ($transaction->transaction_status === TransactionStatusEnum::PAID->value) {
            return response()->json(['message' => 'Already paid'], 200);
        }

        // Cek Status Pembayaran dari Midtrans
        $transactionStatus = $request->transaction_status;
        $fraudStatus = $request->fraud_status;

        if ($transactionStatus == 'capture' || $transactionStatus == 'settlement') {
            if ($fraudStatus == 'challenge') {
                // Jangan diapa-apain dulu kalau statusnya challenge (butuh verifikasi manual Midtrans)
                $transaction->update(['transaction_status' => TransactionStatusEnum::PENDING->value]);
            } else {
                $transaction->update([
                    'transaction_status' => TransactionStatusEnum::PAID->value,
                    'payment_method' => $request->payment_type,
                    'payment_reference' => $request->transaction_id,
                    'paid_at' => now()
                ]);

                // Generate Tiket
                foreach ($transaction->transactionItems as $item) {
                    for ($i = 0; $i < $item->quantity; $i++) {
                        
                        //Create tiket dengan kode sementara
                        $ticket = Ticket::create([
                            'transaction_id' => $transaction->id,
                            'ticket_category_id' => $item->ticket_category_id,
                            'ticket_code' => 'TEMP-' . Str::random(10), // Kode sementara agar tidak error Unique
                        ]);

                        // Ambil slug kategori, jadikan uppercase
                        $categorySlug = strtoupper($item->ticketCategory->slug);
                        
                        // Susun format baru dan Update tiket tersebut
                        $newTicketCode = "PIFF-{$categorySlug}-" . strtoupper(Str::random(3)) . $ticket->id;
                        
                        $ticket->update([
                            'ticket_code' => $newTicketCode
                        ]);
                        
                    }
                }
            }
        } else if ($transactionStatus == 'cancel' || $transactionStatus == 'deny' || $transactionStatus == 'expire') {
            // Transaksi expired atau failed
            $transaction->update([
                'transaction_status' => TransactionStatusEnum::EXPIRED->value,
                'cancel_reason' => 'Midtrans status: ' . $transactionStatus
            ]);

            // == Untuk mengembalikan kuota ==
            // Karena di awal kita menaikkan sold_count saat status masih draft,
            // maka kalau gagal/expired, sold_count harus kita kurangi lagi agar tiket bisa dibeli orang lain.
            foreach ($transaction->transactionItems as $item) {
                $item->ticketCategory->decrement('sold_count', $item->quantity);
            }
        } else if ($transactionStatus == 'pending') {
            // Transaksi masuk ke pending (misal user baru milih metode bayar Transfer Bank tapi belum transfer)
            $transaction->update(['transaction_status' => TransactionStatusEnum::PENDING->value]);
        }

        // Balas pesan ke Midtrans bahwa kita sudah menerima datanya dengan baik
        return response()->json(['message' => 'Callback received successfully'], 200);
    }
}