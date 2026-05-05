<?php

namespace App\Mail;

use App\Models\Transaction;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Attachment;

class ETicketMail extends BaseMailable
{

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

        $bgImageSrc = '';
        $bgPath = public_path('assets/mail/bg_email.jpg');
        if (file_exists($bgPath)) {
            $bgImageSrc = 'data:image/jpeg;base64,' . base64_encode(file_get_contents($bgPath));
        }

        $pdf = Pdf::loadView('pdf.tickets.bundle', ['transaction' => $transaction, 'bgImageSrc' => $bgImageSrc])
            ->setPaper('A4', 'portrait');

        return [
            Attachment::fromData(
                fn() => $pdf->output(),
                "E-Ticket_{$this->transaction->invoice_code}.pdf"
            )->withMime('application/pdf'),
        ];
    }
}
