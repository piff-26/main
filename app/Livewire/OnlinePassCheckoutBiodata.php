<?php

namespace App\Livewire;

use App\Models\Transaction;
use App\Models\Voucher;
use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Ticket;
use Illuminate\Support\Str;
use App\Models\TransactionItem;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use App\Mail\PaymentPendingUserMail;
use App\Mail\PaymentPendingAdminMail;
use App\Enums\SourceInfoEnum;
use App\Enums\TransactionStatusEnum;
use App\Models\SystemLog;

class OnlinePassCheckoutBiodata extends Component
{
    use WithFileUploads;

    public $transaction;

    public $currentStep = 1;

    // Form Biodata
    public $buyer_name;
    public $buyer_phone;
    public $phone_code = '+62';
    public $phone_number = '';
    public $city;
    public $source_info;

    // Form Voucher
    public $voucher_code;
    public $applied_voucher_id = null;

    // Kalkulasi Harga
    public $original_total = 0;
    public $discount_amount = 0;
    public $grand_total = 0;

    // Upload bukti bayar
    public $payment_proof;

    // Nama pemilik tiket per item: [transaction_item_id => ['Nama 1', 'Nama 2', ...]]
    public array $holderNames = [];

    public array $sourceInfoOptions = [];

    public $agree_tnc = false;
    public $tncRead = false;
    public $tncError = '';

    public function mount($invoice_code)
    {
        $this->sourceInfoOptions = array_column(SourceInfoEnum::cases(), 'value');

        $this->transaction = Transaction::with('transactionItems.onlineTicket')
            ->where('invoice_code', $invoice_code)
            ->firstOrFail();

        if (
            $this->transaction->transaction_status === TransactionStatusEnum::DRAFT->value &&
            $this->transaction->expired_at &&
            now()->greaterThan($this->transaction->expired_at)
        ) {
            $this->transaction->update(['transaction_status' => TransactionStatusEnum::EXPIRED->value]);
            foreach ($this->transaction->transactionItems as $item) {
                // Online pass tak punya kuota offline, tapi kita bisa kurangi sold_count jika ada di online_tickets
                // Tapi saat ini di online_tickets tidak ada column sold_count.
            }
            $this->dispatch('transaction-expired');
        }

        $this->buyer_name     = $this->transaction->buyer_name;
        // Split existing buyer_phone into code + number.
        // Regex (\+\d+) is greedy and would consume all digits (e.g. +62123 → code=+62123, number="").
        // Instead, match against known dial codes (longest first) from countries.json.
        if ($this->transaction->buyer_phone && str_starts_with($this->transaction->buyer_phone, '+')) {
            $countries  = json_decode(file_get_contents(resource_path('data/countries.json')), true);
            $dialCodes  = collect($countries)
                ->pluck('dial_code')
                ->unique()
                ->sortByDesc(fn($c) => strlen($c)) // terpanjang dulu agar +1868 tidak salah cocok ke +1
                ->values();

            $matched = '+62'; // fallback
            foreach ($dialCodes as $code) {
                if (str_starts_with($this->transaction->buyer_phone, $code)) {
                    $matched = $code;
                    break;
                }
            }
            $this->phone_code   = $matched;
            $this->phone_number = substr($this->transaction->buyer_phone, strlen($matched));
        } else {
            $this->phone_number = $this->transaction->buyer_phone ?? '';
        }
        $this->buyer_phone    = $this->transaction->buyer_phone;
        $this->city           = $this->transaction->city;
        $this->source_info    = $this->transaction->source_info;
        $this->discount_amount = $this->transaction->discount_amount;

        foreach ($this->transaction->transactionItems as $item) {
            $this->original_total += ($item->quantity * $item->price);
        }
        $this->calculateTotal();
    }

    public function updated($propertyName, $value)
    {
        $allowedFields = ['buyer_name', 'city', 'source_info'];

        if (in_array($propertyName, $allowedFields)) {
            Transaction::where('id', $this->transaction->id)->update([$propertyName => $value]);
            $this->dispatch('draft-saved', time: now()->format('H:i:s'));
        }

        if (in_array($propertyName, ['phone_code', 'phone_number'])) {
            $combined = $this->phone_code . $this->phone_number;
            $this->buyer_phone = $combined;
            Transaction::where('id', $this->transaction->id)->update(['buyer_phone' => $combined]);
            $this->dispatch('draft-saved', time: now()->format('H:i:s'));
        }
    }

    public function calculateTotal()
    {
        $this->grand_total = max(0, $this->original_total - $this->discount_amount);
    }

    public function nextStep()
    {
        $this->validate([
            'buyer_name'   => 'required|string|max:255',
            'phone_number' => 'required|string|max:20',
            'city'         => 'required|string|max:255',
            'source_info'  => 'required|string',
        ], [
            'required' => ':attribute wajib diisi.',
        ]);

        $this->buyer_phone = $this->phone_code . $this->phone_number;
        $this->currentStep = 2;
    }

    public function previousStep()
    {
        $this->currentStep = 1;
    }

    public function applyVoucher()
    {
        $this->resetErrorBag('voucher_code');

        if (empty($this->voucher_code)) {
            $this->removeVoucher();
            return;
        }

        $voucher = Voucher::where('code', strtoupper($this->voucher_code))
            ->where('status', 'active')
            ->first();

        if (!$voucher) {
            $this->addError('voucher_code', 'Voucher not valid.');
            return;
        }

        if ($voucher->expired_at && now()->greaterThan($voucher->expired_at)) {
            $this->addError('voucher_code', 'Voucher expired.');
            return;
        }

        if ($voucher->used_count >= $voucher->max_uses) {
            $this->addError('voucher_code', 'This voucher is no longer available.');
            return;
        }

        // Validasi Voucher
        if ($voucher->usage_type && $voucher->usage_type->value === 'offline_only') {
            $this->addError('voucher_code', 'Voucher ini tidak bisa digunakan untuk Online Pass.');
            return;
        }

        if ($voucher->online_ticket_id !== null) {
            $hasPassInCart = $this->transaction->transactionItems->contains('online_ticket_id', $voucher->online_ticket_id);
            if (!$hasPassInCart) {
                $this->addError('voucher_code', 'This voucher is not valid for the selected online pass.');
                return;
            }
        }

        if ($voucher->discount_type === 'nominal') {
            $this->discount_amount = $voucher->discount_nominal;
        } else {
            if ($voucher->online_ticket_id !== null) {
                $applicableSubtotal = $this->transaction->transactionItems
                    ->where('online_ticket_id', $voucher->online_ticket_id)
                    ->sum(fn($item) => $item->price * $item->quantity);
            } else {
                $applicableSubtotal = $this->original_total;
            }
            $this->discount_amount = $applicableSubtotal * ($voucher->discount_percentage / 100);
        }

        if ($this->discount_amount > $this->original_total) {
            $this->discount_amount = $this->original_total;
        }

        $this->applied_voucher_id = $voucher->id;
        $this->calculateTotal();

        session()->flash('voucher_success', 'Voucher applied successfully!');
    }

    public function removeVoucher()
    {
        $this->voucher_code       = '';
        $this->applied_voucher_id = null;
        $this->discount_amount    = 0;
        $this->grand_total        = $this->original_total;
    }

    /**
     * Simpan biodata + voucher, lanjut ke step upload bukti bayar
     */
    public function processToPayment()
    {
        $this->tncError = '';

        $this->validate([
            'buyer_name'   => 'required|string|max:255',
            'phone_number' => 'required|string|max:20',
            'city'         => 'required|string|max:255',
            'source_info'  => 'required|string',
        ], [
            'buyer_name.required'   => 'Name is required.',
            'phone_number.required' => 'Phone number is required.',
            'city.required'         => 'City is required.',
            'source_info.required'  => 'Information source is required.',
        ]);

        if (!$this->agree_tnc) {
            $this->tncError = 'You must agree to the Terms and Conditions.';
            return;
        }

        $this->buyer_phone = $this->phone_code . $this->phone_number;

        $this->transaction->update([
            'buyer_name'      => $this->buyer_name,
            'buyer_phone'     => $this->phone_code . $this->phone_number,
            'city'            => $this->city,
            'source_info'     => $this->source_info,
            'total_amount'    => $this->grand_total,
            'discount_amount' => $this->discount_amount,
            'voucher_id'      => $this->applied_voucher_id,
            'agree_tnc'       => true,
        ]);

        // Online Pass tidak memerlukan holder_names, karena melekat ke akun user.

        if ($this->applied_voucher_id) {
            Voucher::where('id', $this->applied_voucher_id)->increment('used_count');
        }

        $this->currentStep = 2;
    }

    /**
     * Upload bukti bayar → set status PENDING → kirim email
     */
    public function uploadPaymentProof()
    {
        $this->validate([
            'payment_proof' => 'required|image|max:2048',
        ], [
            'payment_proof.required' => 'Payment proof required.',
            'payment_proof.image'    => 'File must be image.',
            'payment_proof.max'      => 'Max file size 2MB.',
        ]);

        $path = $this->payment_proof->store('payment-proofs', 'public');

        $this->transaction->update([
            'payment_proof'      => $path,
            'transaction_status' => TransactionStatusEnum::PENDING->value,
        ]);

        // Kirim email ke user
        $user = User::find($this->transaction->user_id);
        if ($user && $user->email) {
            Mail::to($user->email)->send(new PaymentPendingUserMail($this->transaction));
            SystemLog::success('email', "Email pending dikirim ke {$user->email}", $this->transaction->invoice_code);
        }

        $adminEmails = \App\Models\Admin::whereHas('division', fn($q) => $q->whereIn('slug', ['it', 'bph', 'sc', 'sekkon']))
            ->pluck('email')->filter()->toArray();
        if (!empty($adminEmails)) {
            Mail::to($adminEmails)->send(new PaymentPendingAdminMail($this->transaction));
            SystemLog::success('email', 'Email notifikasi admin dijadwalkan', $this->transaction->invoice_code);
        }

        $this->currentStep = 3;
    }

    #[\Livewire\Attributes\On('cancel-transaction')]
    public function cancelTransaction()
    {
        $this->transaction->update([
            'transaction_status' => TransactionStatusEnum::FAILED->value,
            'cancel_reason'      => 'Dibatalkan oleh pembeli.',
        ]);

        // Tidak ada sold_count untuk online pass saat ini

        session()->flash('toast_info', 'Transaction cancelled.');
        return redirect()->route('user.ticket');
    }

    public function render()
    {
        $countries = json_decode(file_get_contents(resource_path('data/countries.json')), true);
        return view('livewire.online-pass-checkout', compact('countries'));
    }
}
