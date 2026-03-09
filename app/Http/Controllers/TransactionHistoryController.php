<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use Barryvdh\DomPDF\Facade\Pdf;

class TransactionHistoryController extends BaseController
{
    public function downloadETicket($invoiceCode)
    {
        // Ambil transaksi milik user yang sedang login & lunas
        $userId = session('user_id');
        $transaction = Transaction::where('invoice_code', $invoiceCode)
            ->where('user_id', $userId)
            ->where('transaction_status', 'paid')
            ->with(['tickets.ticketCategory.event']) // Load relasi tiket
            ->firstOrFail();

        // Render view bundle.blade.php menjadi PDF
        $pdf = Pdf::loadView('pdf.tickets.bundle', ['transaction' => $transaction]);
        
        // Set ukuran kertas (A4 biasanya paling aman untuk di-print)
        $pdf->setPaper('A4', 'portrait');

        // Download file dengan nama invoice
        return $pdf->download("E-Ticket_{$transaction->invoice_code}.pdf");
    }
}
