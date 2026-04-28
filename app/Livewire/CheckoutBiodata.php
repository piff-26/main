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

class CheckoutBiodata extends Component
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

        $this->transaction = Transaction::with('transactionItems.ticketCategory')
            ->where('invoice_code', $invoice_code)
            ->firstOrFail();

        if (
            $this->transaction->transaction_status === TransactionStatusEnum::DRAFT->value &&
            $this->transaction->expired_at &&
            now()->greaterThan($this->transaction->expired_at)
        ) {
            $this->transaction->update(['transaction_status' => TransactionStatusEnum::EXPIRED->value]);
            foreach ($this->transaction->transactionItems as $item) {
                $item->ticketCategory->decrement('sold_count', $item->quantity);
            }
            $this->dispatch('transaction-expired');
        }

        $this->buyer_name     = $this->transaction->buyer_name;
        // Split existing buyer_phone into code + number if it starts with +
        if ($this->transaction->buyer_phone && str_starts_with($this->transaction->buyer_phone, '+')) {
            preg_match('/^(\+\d+)(.*)$/', $this->transaction->buyer_phone, $m);
            $this->phone_code   = $m[1] ?? '+62';
            $this->phone_number = $m[2] ?? '';
        } else {
            $this->phone_number = $this->transaction->buyer_phone ?? '';
        }
        $this->buyer_phone    = $this->transaction->buyer_phone;
        $this->city           = $this->transaction->city;
        $this->source_info    = $this->transaction->source_info;
        $this->discount_amount = $this->transaction->discount_amount;

        foreach ($this->transaction->transactionItems as $item) {
            $this->original_total += ($item->quantity * $item->price);
            // Pre-fill holder names jika sudah pernah diisi
            if (!isset($this->holderNames[$item->id])) {
                $this->holderNames[$item->id] = $item->holder_names ?? array_fill(0, $item->quantity, '');
            }
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

        if ($voucher->event_id !== null) {
            $transactionEventIds = $this->transaction->transactionItems->pluck('ticketCategory.event_id')->unique();
            if (!$transactionEventIds->contains($voucher->event_id)) {
                $this->addError('voucher_code', 'Voucher is not valid for this event.');
                return;
            }
        }

        if ($voucher->ticket_category_id !== null) {
            $hasCategoryInCart = $this->transaction->transactionItems->contains('ticket_category_id', $voucher->ticket_category_id);
            if (!$hasCategoryInCart) {
                $this->addError('voucher_code', 'This voucher is not valid for the selected ticket category.');
                return;
            }
        }

        if ($voucher->discount_type === 'nominal') {
            $this->discount_amount = $voucher->discount_nominal;
        } else {
            $this->discount_amount = $this->original_total * ($voucher->discount_percentage / 100);
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

        // Validasi holder names dulu sebelum apapun
        $holderRules = [];
        foreach ($this->transaction->transactionItems as $item) {
            for ($i = 0; $i < $item->quantity; $i++) {
                $holderRules["holderNames.{$item->id}.{$i}"] = 'required|string|max:255';
            }
        }
        $this->validate(array_merge([
            'buyer_name'   => 'required|string|max:255',
            'phone_number' => 'required|string|max:20',
            'city'         => 'required|string|max:255',
            'source_info'  => 'required|string',
        ], $holderRules), [
            'buyer_name.required'   => 'Name is required.',
            'phone_number.required' => 'Phone number is required.',
            'city.required'         => 'City is required.',
            'source_info.required'  => 'Information source is required.',
            'required'              => 'Owner name is required.',
            'max'                   => 'Name to long. (give first and last name if possible)',
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

        // Simpan holder names per transaction item
        foreach ($this->transaction->transactionItems as $item) {
            $item->update(['holder_names' => array_values($this->holderNames[$item->id] ?? [])]);
        }

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

        foreach ($this->transaction->transactionItems as $item) {
            $item->ticketCategory->decrement('sold_count', $item->quantity);
        }

        session()->flash('toast_info', 'Transaction cancelled.');
        return redirect()->route('user.ticket');
    }

    public function render()
    {
        $countries = json_decode(file_get_contents(resource_path('data/countries.json')), true);
        return view('livewire.checkout-finish', compact('countries'));
    }
}
