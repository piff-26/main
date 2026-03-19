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

class TransactionController extends Controller
{
    public function storeStep1(Request $request, $eventSlug)
    {
        $request->validate([
            'category_id' => 'required|exists:ticket_categories,id',
            // 'qty' => 'required|integer|min:1|max:5', // Batasi max beli
        ]);

        return DB::transaction(function () use ($request) {
            
            // Lock baris kategori ini di DB agar tidak bentrok
            $category = TicketCategory::where('id', $request->category_id)->lockForUpdate()->first();

            // Cek apakah tiket masih tersedia
            // Jika kolom quota boleh null (unlimited), kita lewati pengecekan.
            // Jika tidak null, pastikan (yang sudah terjual/dihold + yang mau dibeli) tidak melebihi quota.
            if ($category->quota !== null && ($category->sold_count + $request->qty > $category->quota)) {
                return back()->with('error', 'Maaf, sisa tiket tidak mencukupi.');
            }

            //Tambahkan ke Sold Count (Hold Kuota)
            $category->increment('sold_count', $request->qty);

            // Buat record Transaksi dengan status pending
            $invoiceCode = 'INV-' . strtoupper(Str::random(5));
            $transaction = Transaction::create([
                'user_id' => session('user_id'),
                'invoice_code' => $invoiceCode,
                'total_amount' => $category->price * $request->qty, 
                'transaction_status' => 'pending',
                'expired_at' => now()->addMinutes(15), // Kunci selama 15 menit
            ]);

            // Simpan detail keranjangnya
            TransactionItem::create([
                'transaction_id' => $transaction->id,
                'ticket_category_id' => $category->id,
                'quantity' => $request->qty,
                'price' => $category->price
            ]);

            return redirect()->route('checkout.step2', $transaction->invoice_code);
        });
    }


    public function step1($eventSlug)
{
    // Cari event berdasarkan slug, sekalian load kategori tiketnya
    $event = Event::with(['ticketCategories' => function($query) {
        $query->orderBy('price', 'asc'); // Urutkan dari termurah
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
            ->where('transaction_status', 'pending')
            ->firstOrFail();

        return view('user.transactions.transaction', [
            'title' => 'Isi Biodata - ' . $transaction->invoice_code,
            'invoiceCode' => $invoiceCode,
            'expiredAt' => $transaction->expired_at // Untuk info batas waktu
        ]);
    }

    // App\Http\Controllers\TransactionController.php

    public function step3($invoiceCode)
    {
        $transaction = Transaction::where('invoice_code', $invoiceCode)
            ->where('user_id', session('user_id'))
            ->where('transaction_status', 'pending')
            ->firstOrFail();

        return view('user.transactions.step3-confirm', [
            'title' => 'Konfirmasi Pesanan - ' . $transaction->invoice_code,
            'invoiceCode' => $invoiceCode,
            'transaction' => $transaction
        ]);
    }
}
