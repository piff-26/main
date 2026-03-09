<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ticket;
use App\Models\Transaction;
use Barryvdh\DomPDF\Facade\Pdf;

class TicketController extends BaseController
{
    public function downloadETicket($invoiceCode)
    {
        // Ambil data transaksi beserta tiket dan kategori tiketnya
        $transaction = Transaction::with(['tickets.ticketCategory', 'user'])
            ->where('invoice_code', $invoiceCode)
            ->firstOrFail();

        // Pastikan hanya yang sudah lunas yang bisa cetak tiket
        if ($transaction->transaction_status !== 'paid') {
            abort(403, 'Transaksi belum lunas.');
        }

        // Generate PDF menggunakan view master 'bundle'
        $pdf = Pdf::loadView('pdf.tickets.bundle', compact('transaction'));

        // ukuran kertas
        $pdf->setPaper('A4', 'portrait');

        // Download file PDF
        return $pdf->download("E-Ticket-{$invoiceCode}.pdf");
    }

    public function processCheckIn($ticketCode)
    {
        $ticket = Ticket::where('ticket_code', $ticketCode)->first();

        if (!$ticket) {
            return response()->json(['message' => 'Tiket tidak valid!'], 404);
        }

        if ($ticket->is_checked_in) {
            return response()->json(['message' => 'Tiket SUDAH DIGUNAKAN sebelumnya!'], 400);
        }

        // Lakukan check-in
        $ticket->update([
            'is_checked_in' => true,
            'checked_in_at' => now()
        ]);

        return response()->json([
            'message' => 'Check-in Berhasil untuk: ' . $ticket->transaction->buyer_name,
            'ticket_code' => $ticket->ticket_code
        ], 200);
    }
}
