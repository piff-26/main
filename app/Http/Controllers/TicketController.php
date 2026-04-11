<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ticket;
use App\Models\Transaction;
use Barryvdh\DomPDF\Facade\Pdf;

class TicketController extends Controller
{
    public function lookupTicket($ticketCode)
    {
        $ticket = Ticket::with(['ticketCategory.event', 'transaction', 'checker'])
            ->where('ticket_code', $ticketCode)
            ->first();

        if (!$ticket) {
            return response()->json(['status' => 'not_found', 'message' => 'Tiket tidak ditemukan.'], 404);
        }

        if ($ticket->is_canceled) {
            return response()->json(['status' => 'canceled', 'message' => 'Tiket telah dibatalkan.']);
        }

        return response()->json([
            'status'       => $ticket->is_checked_in ? 'checked_in' : 'valid',
            'ticket_code'  => $ticket->ticket_code,
            'holder_name'  => $ticket->holder_name ?? $ticket->transaction->buyer_name,
            'category'     => $ticket->ticketCategory->name,
            'event'        => $ticket->ticketCategory->event->name,
            'checked_in_at'  => $ticket->checked_in_at?->format('d M Y, H:i'),
            'checked_in_by'  => $ticket->checker?->name,
        ]);
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
            'checked_in_at' => now(),
            'checked_in_by' => session('admin_id')
        ]);

        return response()->json([
            'message' => 'Check-in Berhasil untuk: ' . $ticket->transaction->buyer_name,
            'ticket_code' => $ticket->ticket_code
        ], 200);
    }
}
