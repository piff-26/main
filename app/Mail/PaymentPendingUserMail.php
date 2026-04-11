<?php

namespace App\Mail;

use App\Models\Transaction;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class PaymentPendingUserMail extends BaseMailable
{

    public function __construct(public Transaction $transaction) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: '[PIFF 2026] Pembayaran Sedang Diverifikasi - ' . $this->transaction->invoice_code);
    }

    public function content(): Content
    {
        return new Content(view: 'mail.payment-pending-user');
    }
}
