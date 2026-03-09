<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ticket;
use App\Models\Transaction;
use Barryvdh\DomPDF\Facade\Pdf;

class TicketController extends BaseController
{
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
