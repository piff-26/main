<?php

namespace App\Mail;

use App\Models\Transaction;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PaymentPendingAdminMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Transaction $transaction) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: '[PIFF 2026] Verifikasi Pembayaran - ' . $this->transaction->invoice_code);
    }

    public function content(): Content
    {
        return new Content(view: 'mail.payment-pending-admin');
    }
}
