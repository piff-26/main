<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\TicketCategory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Http\Controllers\BaseController;
use App\Models\Event;
use App\Models\User;
use App\Enums\TransactionStatusEnum;

class TransactionController extends Controller
{
    public function storeStep1(Request $request, $eventSlug)
    {
        $request->validate([
            'items' => 'required|array',
            'items.*' => 'integer|min:0|max:5',
        ]);

        // Filter hanya item yang qty > 0
        $selectedItems = collect($request->items)->filter(fn($qty) => $qty > 0);

        if ($selectedItems->isEmpty()) {
            return back()->with('error', 'Pilih minimal 1 tiket.');
        }

        return DB::transaction(function () use ($selectedItems) {

            $totalAmount = 0;
            $itemsToCreate = [];

            foreach ($selectedItems as $categoryId => $qty) {
                $category = TicketCategory::where('id', $categoryId)->lockForUpdate()->first();

                if (!$category) continue;

                if ($category->quota !== null && ($category->sold_count + $qty > $category->quota)) {
                    return back()->with('error', "Sisa tiket {$category->name} tidak mencukupi.");
                }

                $category->increment('sold_count', $qty);
                $totalAmount += $category->price * $qty;
                $itemsToCreate[] = [
                    'ticket_category_id' => $category->id,
                    'quantity' => $qty,
                    'price' => $category->price,
                ];
            }

            $invoiceCode = 'INV-' . strtoupper(Str::random(5));
            $transaction = Transaction::create([
                'user_id' => session('user_id'),
                'invoice_code' => $invoiceCode,
                'total_amount' => $totalAmount,
                'transaction_status' => TransactionStatusEnum::DRAFT->value,
                'expired_at' => now()->addMinutes(15),
            ]);

            foreach ($itemsToCreate as $item) {
                TransactionItem::create(array_merge($item, ['transaction_id' => $transaction->id]));
            }

            return redirect()->route('checkout.step2', $transaction->invoice_code);
        });
    }


    public function step1($eventSlug)
    {
        $userId = session('user_id');

        if ($userId) {
            $active = Transaction::where('user_id', $userId)
                ->whereIn('transaction_status', [TransactionStatusEnum::DRAFT->value, TransactionStatusEnum::PENDING->value])
                ->where('expired_at', '>', now())
                ->latest()
                ->first();

            if ($active) {
                return redirect()->route('checkout.step2', $active->invoice_code)
                    ->with('warning', 'Selesaikan transaksi sebelumnya terlebih dahulu.');
            }
        }

        $event = Event::with(['ticketCategories' => function($query) {
            $query->orderBy('price', 'asc');
        }])->where('slug', $eventSlug)->firstOrFail();

        return view('user.transactions.category', [
            'title' => 'Pilih Tiket - ' . $event->name,
            'event' => $event
        ]);
    }

    public function step2($invoiceCode)
    {
        // Pastikan transaksi valid, milik user yang login, dan statusnya masih pending
        $transaction = Transaction::where('invoice_code', $invoiceCode)
            ->where('user_id', session('user_id'))
            ->whereIn('transaction_status', [TransactionStatusEnum::DRAFT->value, TransactionStatusEnum::PENDING->value])
            ->firstOrFail();

        return view('user.transactions.transaction', [
            'title' => 'Isi Biodata - ' . $transaction->invoice_code,
            'invoiceCode' => $invoiceCode,
            'expiredAt' => $transaction->expired_at ? $transaction->expired_at->setTimezone('Asia/Jakarta') : now()->setTimezone('Asia/Jakarta')->addMinutes(15),
        ]);
    }

    // App\Http\Controllers\TransactionController.php

    public function step3($invoiceCode)
    {
        $transaction = Transaction::where('invoice_code', $invoiceCode)
            ->where('user_id', session('user_id'))
            ->where('transaction_status', TransactionStatusEnum::PENDING->value)
            ->firstOrFail();

        return view('user.transactions.step3-confirm', [
            'title' => 'Konfirmasi Pesanan - ' . $transaction->invoice_code,
            'invoiceCode' => $invoiceCode,
            'transaction' => $transaction
        ]);
    }
}
