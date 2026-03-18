<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Transaction;
use App\Models\Voucher; 

class CheckoutConfirm extends Component
{
    public Transaction $transaction;
    
    public $voucher_code;
    public $discount_amount = 0;
    public $grand_total = 0;
    public $applied_voucher_id = null;

    public function mount($invoice_code)
    {
        $this->transaction = Transaction::with('transactionItems.ticketCategory')
            ->where('invoice_code', $invoice_code)
            ->where('user_id', session('user_id'))
            ->where('transaction_status', 'pending')
            ->firstOrFail();

        $this->applied_voucher_id = $this->transaction->voucher_id;
        $this->discount_amount = $this->transaction->discount_amount ?? 0;
        
        $this->calculateTotal();
    }

    public function applyVoucher()
    {
        $this->validate([
            'voucher_code' => 'required|string'
        ]);

        // Cek ke DB Voucher (Sesuaikan dengan skema tabel vouchermu)
        $voucher = Voucher::where('code', $this->voucher_code)
            ->where('is_active', true)
            ->first();

        if (!$voucher) {
            $this->addError('voucher_code', 'Kode voucher tidak valid atau sudah kadaluarsa.');
            return;
        }

        $this->discount_amount = $voucher->discount_value;
        $this->applied_voucher_id = $voucher->id;

        $this->transaction->update([
            'voucher_id' => $voucher->id,
            'discount_amount' => $this->discount_amount
        ]);

        $this->calculateTotal();
        session()->flash('voucher_success', 'Voucher berhasil diterapkan!');
    }

    public function removeVoucher()
    {
        $this->discount_amount = 0;
        $this->applied_voucher_id = null;
        $this->voucher_code = '';

        $this->transaction->update([
            'voucher_id' => null,
            'discount_amount' => 0
        ]);

        $this->calculateTotal();
    }

    public function calculateTotal()
    {
        $subtotal = $this->transaction->transactionItems->sum(function ($item) {
            return $item->price * $item->quantity;
        });

        $this->grand_total = max(0, $subtotal - $this->discount_amount);
        
        $this->transaction->update([
            'total_amount' => $this->grand_total
        ]);
    }

    public function processToPayment()
    {
        // Langsung lempar ke halaman pembayaran
        return redirect()->route('user.checkout.payment', $this->transaction->invoice_code);
    }

    public function render()
    {
        return view('livewire.checkout-confirm');
    }
}