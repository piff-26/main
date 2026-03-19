<?php

namespace App\Livewire;

use App\Models\Transaction;
use App\Models\Voucher;
use Livewire\Component;
use App\Models\Ticket;
use Illuminate\Support\Str;
use App\Services\MidtransService;
use App\Models\TransactionItem;
use Illuminate\Support\Facades\DB;

class CheckoutBiodata extends Component
{
    public $transaction;

    public $currentStep = 1;
    
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

    public $draftSavedTime;
    public $agree_tnc = false;
    public $tncRead = false;
    public $tncError = '';

    public function mount($invoice_code)
    {
        // Mengambil data transaksi draft
        $this->transaction = Transaction::with('transactionItems.ticketCategory')
            ->where('invoice_code', $invoice_code)
            ->firstOrFail();

        // Load data jika user kembali ke halaman ini (melanjutkan draft)
        $this->buyer_name = $this->transaction->buyer_name;
        $this->buyer_phone = $this->transaction->buyer_phone;
        $this->city = $this->transaction->city;
        $this->source_info = $this->transaction->source_info;
        $this->discount_amount = $this->transaction->discount_amount;

        // Kalkulasi Harga
        foreach ($this->transaction->transactionItems as $item) {
            $this->original_total += ($item->quantity * $item->price);
        }
        $this->calculateTotal();
    }


    public function updated($propertyName, $value)
    {
        $allowedFields = ['buyer_name', 'buyer_phone', 'city', 'source_info'];
        
        if (in_array($propertyName, $allowedFields)) {
            // Save langsung ke Database
            Transaction::where('id', $this->transaction->id)->update([
                $propertyName => $value
            ]);

            // Tembak event ke frontend (bawa data waktu)
            $this->dispatch('draft-saved', time: now()->format('H:i:s'));
        }
    }

    public function calculateTotal()
    {
        $this->grand_total = $this->original_total - $this->discount_amount;
        // Pastikan total tidak minus
        if($this->grand_total < 0) $this->grand_total = 0; 
    }

    public function nextStep()
    {
        $this->validate([
            'buyer_name' => 'required|string|max:255',
            'buyer_phone' => 'required|numeric',
            'city' => 'required|string|max:255',
            'source_info' => 'required|string',
        ], [
            'required' => ':attribute wajib diisi.',
            'numeric' => 'Nomor telepon harus berupa angka.'
        ]);

        $this->currentStep = 2;
    }

    public function previousStep()
    {
        $this->currentStep = 1;
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
        $this->calculateTotal();

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
        $this->tncError = '';

        $this->validate([
            'buyer_name'  => 'required|string|max:255',
            'buyer_phone' => 'required|numeric',
            'city'        => 'required|string|max:255',
            'source_info' => 'required|string',
        ]);

        if (!$this->agree_tnc) {
            $this->tncError = 'Anda harus menyetujui Syarat dan Ketentuan terlebih dahulu.';
            return;
        }

        // Simpan total amount ke DB dan ubah status 
        $this->transaction->update([
            'total_amount' => $this->grand_total,
            'transaction_status' => 'pending' // Update status siap bayar
        ]);

        // TODO: Generate Midtrans Snap Token di sini jika belum ada
        // $this->transaction->snap_token = MidtransService::getSnapToken($this->transaction);
        // $this->transaction->save();

        // Pindah ke halaman pembayaran
        $this->currentStep = 2;
        
        // Trigger Javascript untuk buka popup Midtrans
        if ($this->transaction->snap_token) {
            $this->dispatch('trigger-midtrans', snapToken: $this->transaction->snap_token);
        }
    }

    public function reTriggerMidtrans()
    {
        if ($this->transaction->snap_token) {
            $this->dispatch('trigger-midtrans', snapToken: $this->transaction->snap_token);
        } else {
            $this->processToPayment();
        }
    }

    public function paymentSuccess()
    {
        $this->transaction->update([
            'transaction_status' => 'paid',
            'paid_at'            => now(),
            'agree_tnc'          => true,
        ]);

        if ($this->transaction->tickets()->count() === 0) {
            $transaction = Transaction::with('transactionItems.ticketCategory')->find($this->transaction->id);

            foreach ($transaction->transactionItems as $item) {
                for ($i = 0; $i < $item->quantity; $i++) {
                    $ticket = Ticket::create([
                        'transaction_id'     => $transaction->id,
                        'ticket_category_id' => $item->ticket_category_id,
                        'ticket_code'        => 'TEMP-' . Str::random(10),
                    ]);

                    $categorySlug = strtoupper($item->ticketCategory->slug);
                    $invRandom = substr($transaction->invoice_code, 4); // ambil bagian setelah 'INV-'
                    $newTicketCode = "INV-{$categorySlug}-{$invRandom}-" . strtoupper(Str::random(3));

                    $ticket->update(['ticket_code' => $newTicketCode]);
                }
            }
        }

        $this->currentStep = 3;
    }

    public function render()
    {
        return view('livewire.checkout-finish');
    }
}