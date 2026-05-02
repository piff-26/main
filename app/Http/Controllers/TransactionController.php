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
            'items'   => 'required|array',
            'items.*' => 'integer|min:0|max:10',
        ]);

        // Pastikan semua key adalah UUID yang terdaftar di ticket_categories
        $validCategoryIds = TicketCategory::whereIn('id', array_keys($request->items ?? []))->pluck('id')->all();
        foreach (array_keys($request->items ?? []) as $key) {
            if (!in_array($key, $validCategoryIds)) {
                return back()->with('error', 'Kategori tiket tidak valid.');
            }
        }

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
            // Hanya blok jika ada transaksi DRAFT yang belum expired (masih bisa dilanjutkan)
            // PENDING tidak diblok karena sudah selesai dari sisi user (tinggal tunggu verifikasi admin)
            $active = Transaction::where('user_id', $userId)
                ->where('transaction_status', TransactionStatusEnum::DRAFT->value)
                ->where('expired_at', '>', now())
                ->latest()
                ->first();

            if ($active) {
                return redirect()->route('checkout.step2', $active->invoice_code)
                    ->with('warning', 'Finish you previous transaction.');
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
        // Hanya izinkan akses jika masih DRAFT — PENDING berarti sudah upload bukti bayar
        $transaction = Transaction::where('invoice_code', $invoiceCode)
            ->where('user_id', session('user_id'))
            ->where('transaction_status', TransactionStatusEnum::DRAFT->value)
            ->firstOrFail();

        if ($transaction->expired_at && now()->greaterThan($transaction->expired_at)) {
            $transaction->update(['transaction_status' => TransactionStatusEnum::EXPIRED->value]);
            foreach ($transaction->transactionItems()->with('ticketCategory')->get() as $item) {
                $item->ticketCategory->decrement('sold_count', $item->quantity);
            }
            return redirect()->route('user.ticket')
                ->with('toast_error', 'Transaksi sudah expired. Silakan buat transaksi baru.');
        }

        return view('user.transactions.transaction', [
            'title' => 'Ticket Data - ' . $transaction->invoice_code,
            'invoiceCode' => $invoiceCode,
            'expiredAt' => $transaction->expired_at ? $transaction->expired_at->setTimezone('Asia/Jakarta') : now()->setTimezone('Asia/Jakarta')->addMinutes(15),
        ]);
    }

}
