<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\OnlineTicket;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Enums\TransactionStatusEnum;

class OnlinePassCheckoutController extends Controller
{
    public function step1($slug)
    {
        $userId = session('user_id');

        if ($userId) {
            // Hanya blok jika ada transaksi DRAFT yang belum expired
            $active = Transaction::where('user_id', $userId)
                ->where('transaction_status', TransactionStatusEnum::DRAFT->value)
                ->where('expired_at', '>', now())
                ->latest()
                ->first();

            if ($active) {
                // Determine if the active transaction is an online pass or offline pass.
                // We'll redirect to the appropriate step2 based on what it is.
                $hasOnlineTicket = $active->transactionItems()->whereNotNull('online_ticket_id')->exists();
                
                if ($hasOnlineTicket) {
                    return redirect()->route('online-pass.checkout.step2', $active->invoice_code)
                        ->with('warning', 'Selesaikan transaksi online pass Anda sebelumnya.');
                } else {
                    return redirect()->route('checkout.step2', $active->invoice_code)
                        ->with('warning', 'Selesaikan transaksi tiket offline Anda sebelumnya.');
                }
            }
        }

        $ticket = OnlineTicket::where('slug', $slug)->where('is_active', true)->firstOrFail();

        $hasPurchased = false;
        if ($userId) {
            $hasPurchased = \App\Models\UserOnlinePass::where('user_id', $userId)
                ->where('online_ticket_id', $ticket->id)
                ->where('status', \App\Enums\UserOnlinePassStatusEnum::ACTIVE->value)
                ->exists();
        }

        return view('user.online_pass.category', [
            'title' => 'Pilih Online Pass - ' . $ticket->name,
            'ticket' => $ticket,
            'hasPurchased' => $hasPurchased
        ]);
    }

    public function storeStep1(Request $request, $slug)
    {
        $request->validate([
            'qty' => 'required|integer|min:1|max:1', // Usually you only buy 1 online pass for yourself
        ]);

        $ticket = OnlineTicket::where('slug', $slug)->where('is_active', true)->firstOrFail();

        return DB::transaction(function () use ($ticket, $request) {
            $qty = $request->qty;
            $totalAmount = $ticket->price * $qty;

            $invoiceCode = 'INV-ONL-' . strtoupper(Str::random(5));
            $transaction = Transaction::create([
                'user_id' => session('user_id'),
                'invoice_code' => $invoiceCode,
                'total_amount' => $totalAmount,
                'transaction_status' => TransactionStatusEnum::DRAFT->value,
                'expired_at' => now()->addMinutes(15),
            ]);

            TransactionItem::create([
                'transaction_id' => $transaction->id,
                'online_ticket_id' => $ticket->id,
                'quantity' => $qty,
                'price' => $ticket->price,
            ]);

            return redirect()->route('online-pass.checkout.step2', $transaction->invoice_code);
        });
    }

    public function step2($invoiceCode)
    {
        $transaction = Transaction::with('transactionItems')->where('invoice_code', $invoiceCode)
            ->where('user_id', session('user_id'))
            ->where('transaction_status', TransactionStatusEnum::DRAFT->value)
            ->firstOrFail();

        if ($transaction->expired_at && now()->greaterThan($transaction->expired_at)) {
            $transaction->update(['transaction_status' => TransactionStatusEnum::EXPIRED->value]);
            return redirect()->route('user.ticket')
                ->with('toast_error', 'Transaksi sudah expired. Silakan buat transaksi baru.');
        }

        return view('user.online_pass.transaction', [
            'title' => 'Online Pass Data - ' . $transaction->invoice_code,
            'invoiceCode' => $invoiceCode,
            'expiredAt' => $transaction->expired_at ? $transaction->expired_at->setTimezone('Asia/Jakarta') : now()->setTimezone('Asia/Jakarta')->addMinutes(15),
        ]);
    }
}
