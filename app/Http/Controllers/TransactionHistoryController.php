<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Enums\TransactionStatusEnum;

class TransactionHistoryController extends Controller
{
    public function downloadETicket($invoiceCode)
    {
        // Ambil transaksi milik user yang sedang login & lunas
        $userId = session('user_id');
        $transaction = Transaction::where('invoice_code', $invoiceCode)
            ->where('user_id', $userId)
            ->where('transaction_status', TransactionStatusEnum::PAID->value)
            ->with(['tickets.ticketCategory.event', 'voucher'])
            ->firstOrFail();

        // Encode background image untuk tiket Regular
        $bgImageSrc = '';
        $bgPath = public_path('assets/mail/bg_email.jpg');
        if (file_exists($bgPath)) {
            $bgImageSrc = 'data:image/jpeg;base64,' . base64_encode(file_get_contents($bgPath));
        }

        // Render view bundle.blade.php menjadi PDF
        $pdf = Pdf::loadView('pdf.tickets.bundle', ['transaction' => $transaction, 'bgImageSrc' => $bgImageSrc]);
        
        // Set ukuran kertas (A4 biasanya paling aman untuk di-print)
        $pdf->setPaper('A4', 'portrait');

        // Download file dengan nama invoice
        return $pdf->download("E-Ticket_{$transaction->invoice_code}.pdf");
    }
}
