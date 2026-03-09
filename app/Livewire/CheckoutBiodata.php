<?php

namespace App\Livewire;

use App\Models\Transaction;
use App\Models\Voucher;
use Livewire\Component;
use Illuminate\Support\Facades\DB;

class CheckoutBiodata extends Component
{
    public $transaction;
    
    // Form Biodata
    public $buyer_name;
    public $buyer_phone;
    public $city;
    public $source_info;

    // Form Voucher
    public $voucher_code;
    public $applied_voucher_id = null;
    
    // Kalkulasi Harga
    public $original_total = 0;
    public $discount_amount = 0;
    public $grand_total = 0;

    public function mount($transactionId)
    {
        // Ambil transaksi yang sedang draft beserta item keranjangnya
        $this->transaction = Transaction::with('transactionItems.ticketCategory')->findOrFail($transactionId);

        // Hitung harga asli sebelum diskon (Quantity * Price)
        foreach ($this->transaction->transactionItems as $item) {
            $this->original_total += ($item->quantity * $item->price);
        }

        $this->grand_total = $this->original_total;
    }

    /**
     * Fungsi Cek dan Terapkan Voucher secara Real-time
     */
    public function applyVoucher()
    {
        $this->resetErrorBag('voucher_code');

        if (empty($this->voucher_code)) {
            $this->removeVoucher();
            return;
        }

        // Cari Voucher yang aktif
        $voucher = Voucher::where('code', strtoupper($this->voucher_code))
            ->where('status', 'active')
            ->first();

        // Validasi 1: Apakah voucher ada?
        if (!$voucher) {
            $this->addError('voucher_code', 'Kode voucher tidak valid.');
            return;
        }

        // Validasi 2: Apakah sudah expired?
        if ($voucher->expired_at && now()->greaterThan($voucher->expired_at)) {
            $this->addError('voucher_code', 'Kode voucher sudah kedaluwarsa.');
            return;
        }

        // Validasi 3: Apakah kuota penukaran masih ada?
        if ($voucher->used_count >= $voucher->max_uses) {
            $this->addError('voucher_code', 'Kuota voucher sudah habis.');
            return;
        }

        // Validasi 4: Pengecekan Scope (Jika voucher khusus event/kategori tertentu)
        // (Kita skip dulu detail ini agar simpel, tapi logikanya tinggal cek relasi item di keranjang)

        // Terapkan Diskon
        if ($voucher->discount_type === 'nominal') {
            $this->discount_amount = $voucher->discount_nominal;
        } else {
            // Jika persentase
            $this->discount_amount = $this->original_total * ($voucher->discount_percentage / 100);
        }

        // Pastikan diskon tidak lebih besar dari total belanja
        if ($this->discount_amount > $this->original_total) {
            $this->discount_amount = $this->original_total;
        }

        $this->grand_total = $this->original_total - $this->discount_amount;
        $this->applied_voucher_id = $voucher->id;

        session()->flash('voucher_success', 'Voucher berhasil diterapkan!');
    }

    /**
     * Fungsi hapus voucher (jika user berubah pikiran)
     */
    public function removeVoucher()
    {
        $this->voucher_code = '';
        $this->applied_voucher_id = null;
        $this->discount_amount = 0;
        $this->grand_total = $this->original_total;
    }

    /**
     * Simpan Data & Lanjut Bayar
     */
    public function processToPayment()
    {
        // Validasi Biodata
        $this->validate([
            'buyer_name' => 'required|string|max:255',
            'buyer_phone' => 'required|numeric|min_digits:9',
            'city' => 'required|string|max:100',
            'source_info' => 'required|in:Social Media,Website resmi,Iklan,Poster,Teman,Dosen',
        ], [
            'source_info.in' => 'Silakan pilih sumber info yang valid.'
        ]);

        try {
            DB::beginTransaction();

            // Update Data Transaksi
            $this->transaction->update([
                'buyer_name' => $this->buyer_name,
                'buyer_phone' => $this->buyer_phone,
                'city' => $this->city,
                'source_info' => $this->source_info,
                'voucher_id' => $this->applied_voucher_id,
                'discount_amount' => $this->discount_amount,
                'total_amount' => $this->grand_total,
                'transaction_status' => 'pending', // Berubah dari draft jadi pending!
            ]);

            // Tambah used_count pada voucher (jika pakai voucher)
            if ($this->applied_voucher_id) {
                Voucher::where('id', $this->applied_voucher_id)->increment('used_count');
            }

            DB::commit();

            // Redirect ke halaman / function Payment Gateway (Midtrans)
            return redirect()->route('user.checkout.payment', $this->transaction->invoice_code);

        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Terjadi kesalahan saat memproses data. Silakan coba lagi.');
        }
    }

    public function render()
    {
        return view('livewire.checkout-biodata');
    }
}