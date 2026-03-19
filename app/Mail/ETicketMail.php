<?php

namespace App\Mail;

use App\Models\Transaction;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Queue\SerializesModels;

class ETicketMail extends Mailable
{
    use Queueable;

    public function __construct(public Transaction $transaction) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'E-Ticket ' . $this->transaction->invoice_code . ' - PIFF 2026');
    }

    public function content(): Content
    {
        return new Content(view: 'mail.eticket');
    }

    public function attachments(): array
    {
        $transaction = Transaction::with('tickets.ticketCategory.event', 'transactionItems.ticketCategory')
            ->find($this->transaction->id);

        $pdf = Pdf::loadView('pdf.tickets.bundle', ['transaction' => $transaction])
            ->setPaper('A4', 'portrait');

        return [
            Attachment::fromData(
                fn() => $pdf->output(),
                "E-Ticket_{$this->transaction->invoice_code}.pdf"
            )->withMime('application/pdf'),
        ];
    }
}
