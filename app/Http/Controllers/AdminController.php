<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Admin;
use App\Models\User;
use App\Models\Event;
use App\Models\Transaction;
use App\Models\TicketCategory;
use App\Models\Ticket;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Voucher;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use App\Enums\TransactionStatusEnum;
use App\Mail\PaymentApprovedMail;
use App\Mail\PaymentRejectedMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Models\SystemLog;

class AdminController extends BaseController
{
    protected $user;

    public function __construct()
    {
        parent::__construct(new Admin());
        $this->user = new User();
    }

    public function loginView()
    {
        return view('admin.login', ['title' => 'Admin Login']);
    }

    public function index()
    {
        $totalRevenue = Transaction::where('transaction_status', TransactionStatusEnum::PAID->value)->sum('total_amount');
        $ticketsSold = Ticket::where('is_canceled', false)->count();
        $totalTickets = TicketCategory::sum('quota');
        $totalTransactions = Transaction::whereIn('transaction_status', [TransactionStatusEnum::PAID->value, TransactionStatusEnum::FAILED->value, TransactionStatusEnum::EXPIRED->value])->count();
        $totalCheckin = Ticket::where('is_checked_in', true)->where('is_canceled', false)->count();
        $totalUsers = User::count();
        $paidTransactions = Transaction::where('transaction_status', TransactionStatusEnum::PAID->value)->count();
        $failedTransactions = Transaction::whereIn('transaction_status', [TransactionStatusEnum::FAILED->value, TransactionStatusEnum::EXPIRED->value])->count();
        
        $ticketCategories = TicketCategory::selectRaw('name, sold_count as sold')->get();
        
        $checkinByCategory = TicketCategory::leftJoin('tickets', 'ticket_categories.id', '=', 'tickets.ticket_category_id')
            ->selectRaw('ticket_categories.name, 
                SUM(CASE WHEN tickets.is_checked_in = 1 AND tickets.is_canceled = 0 THEN 1 ELSE 0 END) as checked_in,
                SUM(CASE WHEN (tickets.is_checked_in = 0 OR tickets.is_checked_in IS NULL) AND tickets.is_canceled = 0 THEN 1 ELSE 0 END) as not_checked_in')
            ->groupBy('ticket_categories.id', 'ticket_categories.name')
            ->get();

        $eventStats = Event::with('ticketCategories')->get()->map(function($event) {
            $pendingCount = Transaction::where('transaction_status', TransactionStatusEnum::PENDING->value)
                ->whereHas('transactionItems.ticketCategory', fn($q) => $q->where('event_id', $event->id))
                ->count();
            $totalRevenue = Transaction::where('transaction_status', TransactionStatusEnum::PAID->value)
                ->whereHas('transactionItems.ticketCategory', fn($q) => $q->where('event_id', $event->id))
                ->sum('total_amount');
            return [
                'name'          => $event->name,
                'pending_count' => $pendingCount,
                'total_revenue' => (float) $totalRevenue,
            ];
        });
        
        return view('admin.dashboard', compact('totalRevenue', 'ticketsSold', 'totalTickets', 'totalTransactions', 'totalCheckin', 'totalUsers', 'paidTransactions', 'failedTransactions', 'ticketCategories', 'checkinByCategory', 'eventStats'));
    }

    public function listEvents()
    {
        $events = Event::with('ticketCategories')->get()->map(function($event) {
            $revenue = Transaction::where('transaction_status', TransactionStatusEnum::PAID->value)
                ->whereHas('transactionItems.ticketCategory', function($q) use ($event) {
                    $q->where('event_id', $event->id);
                })->sum('total_amount');
            
            $transactions = Transaction::whereHas('transactionItems.ticketCategory', function($q) use ($event) {
                $q->where('event_id', $event->id);
            })->count();
            
            $checkins = Ticket::where('is_checked_in', true)->where('is_canceled', false)
                ->whereHas('ticketCategory', function($q) use ($event) {
                    $q->where('event_id', $event->id);
                })->count();
            
            $event->stats = [
                'revenue' => $revenue,
                'ticketsSold' => $event->ticketCategories->sum('sold_count'),
                'transactions' => $transactions,
                'checkins' => $checkins
            ];
            return $event;
        });
        
        return view('admin.event', compact('events'));
    }

    public function createEvent()
    {
        return view('admin.event-create', ['title' => 'Create Event']);
    }

    public function eventDetail($id)
    {
        $event = Event::with(['ticketCategories'])->findOrFail($id);

        // Stats per kategori
        $categoryStats = $event->ticketCategories->map(function ($cat) {
            $revenue = Transaction::where('transaction_status', TransactionStatusEnum::PAID->value)
                ->whereHas('transactionItems', fn($q) => $q->where('ticket_category_id', $cat->id))
                ->join('transaction_items', function($join) use ($cat) {
                    $join->on('transactions.id', '=', 'transaction_items.transaction_id')
                         ->where('transaction_items.ticket_category_id', $cat->id);
                })
                ->sum('transaction_items.price');

            // Hitung revenue sebagai: price * quantity dari transaction items yang paid
            $revenueCalc = \DB::table('transaction_items')
                ->join('transactions', 'transactions.id', '=', 'transaction_items.transaction_id')
                ->where('transactions.transaction_status', TransactionStatusEnum::PAID->value)
                ->where('transaction_items.ticket_category_id', $cat->id)
                ->selectRaw('SUM(transaction_items.price * transaction_items.quantity) as total')
                ->value('total') ?? 0;

            $ticketsSold = Ticket::where('ticket_category_id', $cat->id)
                ->where('is_canceled', false)->count();

            $transactions = Transaction::whereHas('transactionItems', fn($q) => $q->where('ticket_category_id', $cat->id))
                ->where('transaction_status', TransactionStatusEnum::PAID->value)
                ->count();

            $checkins = Ticket::where('ticket_category_id', $cat->id)
                ->where('is_canceled', false)
                ->where('is_checked_in', true)
                ->count();

            return [
                'id'          => $cat->id,
                'name'        => $cat->name,
                'price'       => $cat->price,
                'quota'       => $cat->quota,
                'sold_count'  => $cat->sold_count,
                'revenue'     => $revenueCalc,
                'tickets_sold'=> $ticketsSold,
                'transactions'=> $transactions,
                'checkins'    => $checkins,
            ];
        });

        // Total stats
        $totalRevenue = $categoryStats->sum('revenue');
        $totalTicketsSold = $categoryStats->sum('tickets_sold');
        $totalTransactions = Transaction::where('transaction_status', TransactionStatusEnum::PAID->value)
            ->whereHas('transactionItems.ticketCategory', fn($q) => $q->where('event_id', $id))
            ->count();
        $totalCheckins = $categoryStats->sum('checkins');

        // Semua tiket individu untuk event ini (pagination via JS / DataTables)
        $tickets = Ticket::with(['ticketCategory', 'transaction.user', 'checker'])
            ->whereHas('ticketCategory', fn($q) => $q->where('event_id', $id))
            ->where('is_canceled', false)
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(fn($t) => [
                'id'              => $t->id,
                'ticket_code'     => $t->ticket_code,
                'holder_name'     => $t->holder_name ?? '-',
                'category_name'   => $t->ticketCategory->name ?? '-',
                'category_id'     => $t->ticket_category_id,
                'buyer_name'      => $t->transaction->buyer_name ?? ($t->transaction->user->name ?? '-'),
                'buyer_email'     => $t->transaction->user->email ?? '-',
                'invoice_code'    => $t->transaction->invoice_code ?? '-',
                'is_checked_in'   => $t->is_checked_in,
                'checked_in_at'   => $t->checked_in_at ? $t->checked_in_at->setTimezone('Asia/Jakarta')->format('d M Y, H:i') : null,
                'checker_name'    => $t->checker->name ?? '-',
            ]);

        return view('admin.event-detail', compact(
            'event', 'categoryStats', 'tickets',
            'totalRevenue', 'totalTicketsSold', 'totalTransactions', 'totalCheckins'
        ));
    }

    public function exportEventTicketsExcel($id)
    {
        $event = Event::findOrFail($id);

        $tickets = Ticket::with(['ticketCategory', 'transaction.user', 'checker'])
            ->whereHas('ticketCategory', fn($q) => $q->where('event_id', $id))
            ->where('is_canceled', false)
            ->orderBy('created_at', 'desc')
            ->get();

        $rows = [['No', 'Ticket Code', 'Holder Name', 'Category', 'Buyer Name', 'Email', 'Invoice', 'Status', 'Checked In At', 'Staff']];
        foreach ($tickets as $i => $t) {
            $rows[] = [
                $i + 1,
                $t->ticket_code,
                $t->holder_name ?? '-',
                $t->ticketCategory->name ?? '-',
                $t->transaction->buyer_name ?? ($t->transaction->user->name ?? '-'),
                $t->transaction->user->email ?? '-',
                $t->transaction->invoice_code ?? '-',
                $t->is_checked_in ? 'Checked In' : 'Not Checked In',
                $t->checked_in_at ? $t->checked_in_at->setTimezone('Asia/Jakarta')->format('d M Y H:i') : '-',
                $t->checker->name ?? '-',
            ];
        }

        $filename = 'Tickets_' . str_replace(' ', '_', $event->name) . '_' . now()->format('Ymd_His') . '.csv';

        $handle = fopen('php://output', 'w');
        ob_start();
        foreach ($rows as $row) {
            fputcsv($handle, $row);
        }
        $content = ob_get_clean();
        fclose($handle);

        return response($content, 200, [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }


    public function listCategories()
    {
        $categories = TicketCategory::with('event')->get();
        $events = Event::pluck('name', 'id');
        return view('admin.category', compact('categories', 'events'));
    }

    // --- TRANSACTIONS ---
    public function transaction()
    {
        $transactions = Transaction::with(['user', 'voucher', 'transactionItems.ticketCategory.event'])->get()->map(function($transaction) {
            $eventName = $transaction->transactionItems->first()?->ticketCategory?->event?->name ?? '-';
            return [
                'invoice_code' => $transaction->invoice_code,
                'event_name' => $eventName,
                'buyer_name' => $transaction->buyer_name ?? $transaction->user->name,
                'email' => $transaction->user->email,
                'buyer_phone' => $transaction->buyer_phone,
                'city' => $transaction->city,
                'total_amount' => (float) $transaction->total_amount,
                'voucher_code' => $transaction->voucher?->code ?? '-',
                'transaction_status' => $transaction->transaction_status,
                'created_at' => $transaction->created_at->format('Y-m-d H:i'),
                'paid_at' => $transaction->paid_at ? $transaction->paid_at->format('Y-m-d H:i') : '-'
            ];
        });
        
        $events = Event::pluck('name')->unique()->values();
        $cities = Transaction::whereNotNull('city')->pluck('city')->unique()->values();
        
        return view('admin.transaction.transaction', compact('transactions', 'events', 'cities'));
    }

    public function transactionDetail($invoice_code)
    {
        $transaction = Transaction::with(['user', 'voucher', 'transactionItems.ticketCategory.event', 'tickets.ticketCategory'])
            ->where('invoice_code', $invoice_code)
            ->firstOrFail();
        
        return view('admin.transaction.transactionDetail', compact('transaction'));
    }

    public function exportTransactionPDF($invoice_code)
    {
        $transaction = Transaction::with(['user', 'voucher', 'transactionItems.ticketCategory', 'tickets.ticketCategory.event'])
            ->where('invoice_code', $invoice_code)
            ->firstOrFail();

        $bgImageSrc = '';
        $bgPath = public_path('assets/mail/bg_email.jpg');
        if (file_exists($bgPath)) {
            $bgImageSrc = 'data:image/jpeg;base64,' . base64_encode(file_get_contents($bgPath));
        }

        $pdf = Pdf::loadView('pdf.tickets.bundle', compact('transaction', 'bgImageSrc'));

        return $pdf->download('E-Ticket_' . $invoice_code . '.pdf');
    }

    // --- MONITOR & SCAN ---
    public function monitor(Request $request)
    {
        $selectedEvent = $request->query('event');
        $ticketQuery = Ticket::where('is_canceled', false);
        
        if ($selectedEvent) {
            $ticketQuery->whereHas('ticketCategory.event', function($q) use ($selectedEvent) {
                $q->where('name', $selectedEvent);
            });
        }

        // Ambil Stats General
        $totalCheckedIn = (clone $ticketQuery)->where('is_checked_in', true)->count();
        $capacity       = (clone $ticketQuery)->count();
        
        $categoryStatsQuery = TicketCategory::query();
        if ($selectedEvent) {
            $categoryStatsQuery->whereHas('event', fn($e) => $e->where('name', $selectedEvent));
        }
        $categoryStats = $categoryStatsQuery
            ->withCount(['tickets as total' => function($q) { $q->where('is_canceled', false); }])
            ->withCount(['tickets as checked_in' => function($q) { $q->where('is_canceled', false)->where('is_checked_in', true); }])
            ->get();

        $remaining      = max(0, $capacity - $totalCheckedIn);

        $firstCheckin = (clone $ticketQuery)->where('is_checked_in', true)->min('checked_in_at');
        $avgPerHour   = 0;
        if ($firstCheckin) {
            $hours = max(1, now()->diffInHours($firstCheckin));
            $avgPerHour = round($totalCheckedIn / $hours);
        }

        // Terapkan Base Query ke Log Check-in
        $logs = (clone $ticketQuery)->with(['ticketCategory.event', 'checker'])
            ->where('is_checked_in', true)
            ->whereNotNull('checked_in_at')
            ->orderByDesc('checked_in_at')
            ->limit(50)
            ->get()
            ->map(fn($t) => (object)[
                'ticket_code'   => $t->ticket_code,
                'category_name' => $t->ticketCategory->name ?? '-',
                'event_name'    => $t->ticketCategory->event->name ?? '-',
                'holder_name'   => $t->holder_name ?? '-',
                'checked_at'    => $t->checked_in_at,
                'staff_name'    => $t->checker?->name ?? '-',
            ]);

        $events = Event::orderBy('name')->pluck('name', 'id');

        // Terapkan Base Query ke Chart Data (Per jam)
        $chartData = [];
        $maxCount  = 1;
        for ($i = 7; $i >= 0; $i--) {
            $hour  = now()->subHours($i);
            // Gunakan clone lagi agar kondisinya aman per loop
            $count = (clone $ticketQuery)->where('is_checked_in', true)
                ->whereBetween('checked_in_at', [$hour->copy()->startOfHour(), $hour->copy()->endOfHour()])
                ->count();
                
            $chartData[] = ['hour' => $hour->format('H:00'), 'count' => $count];
            if ($count > $maxCount) $maxCount = $count;
        }
        
        foreach ($chartData as &$d) {
            $d['percentage'] = round(($d['count'] / $maxCount) * 100);
        }

        return view('admin.monitor', [
            'stats' => [
                'total_checked_in' => $totalCheckedIn,
                'remaining'        => max(0, $capacity - $totalCheckedIn),
                'capacity'         => $capacity,
                'fill_percentage'  => $capacity > 0 ? round(($totalCheckedIn / $capacity) * 100) : 0,
            ],
            'categoryStats' => $categoryStats,
            'logs'          => $logs,
            'chart_data'    => $chartData,
            'events'        => $events,
        ]);
    }

    public function ticketScan()
    {
        return view('admin.ticketScan', ['title' => 'Ticket Scan']);
    }

    public function storeEvent(Request $request)
    {
        try {
            $request->validate([
                'name'       => 'required|string|max:255',
                'event_date' => 'required|date|after_or_equal:today',
                'start_time' => 'required',
                'end_time'   => 'required|after:start_time',
                'location'   => 'required|string|max:255',
                'image'          => 'nullable|image|max:2048',
                'seat_map_image' => 'nullable|image|max:2048',
            ]);

            $data = [
                'name'       => $request->name,
                'slug'       => \Str::slug($request->name),
                'event_date' => $request->event_date,
                'start_time' => $request->start_time,
                'end_time'   => $request->end_time,
                'location'   => $request->location,
                'event_closed' => $request->event_closed ?: null,
            ];

            if ($request->hasFile('image')) {
                $data['image'] = $request->file('image')->store('events', 'public');
            }

            if ($request->hasFile('seat_map_image')) {
                $data['seat_map_image'] = $request->file('seat_map_image')->store('events', 'public');
            }

            $data['description'] = $request->description;
            $data['tnc']         = $request->tnc;

            Event::create($data);

            return response()->json(['success' => true, 'message' => 'Event created successfully']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 422);
        }
    }

    public function updateEvent(Request $request, $id)
    {
        try {
            $event = Event::findOrFail($id);

            $request->validate([
                'name'       => 'required|string|max:255',
                'event_date' => 'required|date|after_or_equal:today',
                'start_time' => 'required',
                'end_time'   => 'required|after:start_time',
                'location'   => 'required|string|max:255',
                'image'          => 'nullable|image|max:2048',
                'seat_map_image' => 'nullable|image|max:2048',
            ]);

            $data = [
                'name'       => $request->name,
                'slug'       => \Str::slug($request->name),
                'event_date' => $request->event_date,
                'start_time' => $request->start_time,
                'end_time'   => $request->end_time,
                'location'   => $request->location,
                'event_closed' => $request->event_closed ?: null,
            ];

            if ($request->hasFile('image')) {
                if ($event->image) {
                    \Storage::disk('public')->delete($event->image);
                }
                $data['image'] = $request->file('image')->store('events', 'public');
            }

            if ($request->hasFile('seat_map_image')) {
                if ($event->seat_map_image) {
                    \Storage::disk('public')->delete($event->seat_map_image);
                }
                $data['seat_map_image'] = $request->file('seat_map_image')->store('events', 'public');
            }

            $data['description'] = $request->description;
            $data['tnc']         = $request->tnc;

            $event->update($data);

            return response()->json(['success' => true, 'message' => 'Event updated successfully']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 422);
        }
    }

    public function deleteEvent($id)
    {
        try {
            $event = Event::findOrFail($id);
            
            // Check if event has categories with sold tickets
            $hasSoldTickets = $event->ticketCategories()->where('sold_count', '>', 0)->exists();
            if ($hasSoldTickets) {
                return response()->json(['success' => false, 'message' => 'Cannot delete event with sold tickets'], 422);
            }
            
            $event->delete();
            return response()->json(['success' => true, 'message' => 'Event deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 422);
        }
    }

    public function storeCategory(Request $request)
    {
        $request->validate([
            'event_id' => 'required|exists:events,id',
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'quota' => 'required|integer|min:1',
        ]);

        TicketCategory::create([
            'event_id' => $request->event_id,
            'name' => $request->name,
            'slug' => \Str::slug($request->name),
            'price' => $request->price,
            'quota' => $request->quota,
        ]);

        return response()->json(['success' => true, 'message' => 'Category created successfully']);
    }

    public function updateCategory(Request $request, $id)
    {
        $category = TicketCategory::findOrFail($id);
        
        $request->validate([
            'event_id' => 'required|exists:events,id',
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'quota' => 'required|integer|min:' . $category->sold_count,
        ]);

        $category->update([
            'event_id' => $request->event_id,
            'name' => $request->name,
            'slug' => \Str::slug($request->name),
            'price' => $request->price,
            'quota' => $request->quota,
        ]);

        return response()->json(['success' => true, 'message' => 'Category updated successfully']);
    }

    public function toggleCategory($id)
    {
        try {
            $category = TicketCategory::with('event')->findOrFail($id);

            // Cannot open a category if the parent event is already closed by event_closed
            if ($category->is_closed && $category->event && $category->event->isClosed()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak bisa membuka kategori karena event sudah ditutup (event_closed sudah lewat).'
                ], 422);
            }

            $category->update(['is_closed' => !$category->is_closed]);

            $status = $category->is_closed ? 'closed' : 'opened';
            return response()->json([
                'success'   => true,
                'is_closed' => $category->is_closed,
                'message'   => "Category {$status} successfully."
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 422);
        }
    }

    public function deleteCategory($id)
    {
        try {
            $category = TicketCategory::findOrFail($id);
            
            // Check if category has sold tickets
            if ($category->sold_count > 0) {
                return response()->json(['success' => false, 'message' => 'Cannot delete category with sold tickets'], 422);
            }
            
            $category->delete();
            return response()->json(['success' => true, 'message' => 'Category deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 422);
        }
    }

    public function validatePayment($invoice_code)
    {
        try {
            $transaction = Transaction::with('transactionItems.ticketCategory', 'user')->where('invoice_code', $invoice_code)->firstOrFail();

            if ($transaction->transaction_status !== TransactionStatusEnum::PENDING->value) {
                return response()->json(['success' => false, 'message' => 'Transaksi tidak dalam status pending.'], 422);
            }

            $transaction->update([
                'transaction_status' => TransactionStatusEnum::PAID->value,
                'paid_at'            => now(),
            ]);

            // Generate tiket
            if ($transaction->tickets()->count() === 0) {
                foreach ($transaction->transactionItems as $item) {
                    $holderNames = $item->holder_names ?? [];
                    for ($i = 0; $i < $item->quantity; $i++) {
                        $ticket = Ticket::create([
                            'transaction_id'     => $transaction->id,
                            'ticket_category_id' => $item->ticket_category_id,
                            'ticket_code'        => 'TEMP-' . Str::random(10),
                            'holder_name'        => $holderNames[$i] ?? null,
                        ]);
                        $categorySlug  = strtoupper($item->ticketCategory->slug);
                        $invRandom     = substr($transaction->invoice_code, 4);
                        $newTicketCode = "INV-{$categorySlug}-{$invRandom}-" . strtoupper(Str::random(3));
                        $ticket->update(['ticket_code' => $newTicketCode]);
                        SystemLog::success('ticket', "Tiket digenerate: {$newTicketCode}", $invoice_code, ['holder' => $holderNames[$i] ?? '-', 'category' => $item->ticketCategory->name]);
                    }
                }
            }

            // Kirim email ke user
            $transaction->refresh()->load('tickets.ticketCategory.event', 'transactionItems.ticketCategory');
            if ($transaction->user && $transaction->user->email) {
                Mail::to($transaction->user->email)->send(new PaymentApprovedMail($transaction));
                SystemLog::success('email', "Email approval dikirim ke {$transaction->user->email}", $invoice_code);
            }

            return response()->json(['success' => true, 'message' => 'Pembayaran berhasil divalidasi.']);
        } catch (\Exception $e) {
            SystemLog::fail('ticket', "Gagal validasi payment: {$e->getMessage()}", $invoice_code);
            return response()->json(['success' => false, 'message' => $e->getMessage()], 422);
        }
    }

    public function rejectPayment(Request $request, $invoice_code)
    {
        try {
            $request->validate(['reason' => 'required|string|max:500']);

            $transaction = Transaction::with('transactionItems.ticketCategory', 'user')->where('invoice_code', $invoice_code)->firstOrFail();

            if ($transaction->transaction_status !== TransactionStatusEnum::PENDING->value) {
                return response()->json(['success' => false, 'message' => 'Transaksi tidak dalam status pending.'], 422);
            }

            $transaction->update([
                'transaction_status' => TransactionStatusEnum::FAILED->value,
                'rejection_reason'   => $request->reason,
            ]);

            // Kembalikan quota
            foreach ($transaction->transactionItems as $item) {
                $item->ticketCategory->decrement('sold_count', $item->quantity);
            }

            // Kirim email ke user
            if ($transaction->user && $transaction->user->email) {
                Mail::to($transaction->user->email)->send(new PaymentRejectedMail($transaction));
                SystemLog::success('email', "Email penolakan dikirim ke {$transaction->user->email}", $invoice_code);
            }

            return response()->json(['success' => true, 'message' => 'Pembayaran berhasil ditolak.']);
        } catch (\Exception $e) {
            SystemLog::fail('email', "Gagal kirim email penolakan: {$e->getMessage()}", $invoice_code);
            return response()->json(['success' => false, 'message' => $e->getMessage()], 422);
        }
    }

    public function cancelTransaction($invoice_code)
    {
        try {
            $transaction = Transaction::where('invoice_code', $invoice_code)->firstOrFail();
            
            // Prevent canceling already completed or failed transactions
            if (in_array($transaction->transaction_status, [TransactionStatusEnum::EXPIRED->value, TransactionStatusEnum::FAILED->value])) {
                return response()->json(['success' => false, 'message' => 'Transaction already cancelled or failed'], 422);
            }
            
            if ($transaction->transaction_status === TransactionStatusEnum::PAID->value) {
                // Refund: Update transaction and cancel tickets
                $transaction->update(['transaction_status' => TransactionStatusEnum::EXPIRED->value]);
                $transaction->tickets()->update(['is_canceled' => true, 'canceled_at' => now()]);
                
                // Decrease sold_count for each ticket category
                foreach ($transaction->tickets as $ticket) {
                    $ticket->ticketCategory->decrement('sold_count');
                }
            } else {
                // Cancel draft: Just update status
                $transaction->update(['transaction_status' => TransactionStatusEnum::EXPIRED->value]);
            }
            
            return response()->json(['success' => true, 'message' => 'Transaction cancelled successfully']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 422);
        }
    }

    public function systemLog(Request $request)
    {
        $query = SystemLog::latest();
        if ($request->type)   $query->where('type', $request->type);
        if ($request->status) $query->where('status', $request->status);
        $logs = $query->paginate(50)->withQueryString();
        return view('admin.system-log', compact('logs'));
    }

    // --- MANAGE VOUCHERS ---
    public function listVouchers()
    {
        $vouchers = Voucher::withTrashed()->with(['event', 'ticketCategory'])->get();
        $events = Event::all();
        $ticketCategories = TicketCategory::with('event')->get();

        return view('admin.manageVouchers', [
            'title' => 'Manage Vouchers',
            'vouchers' => $vouchers,
            'events' => $events,
            'ticketCategories' => $ticketCategories,
        ]);
    }

    public function storeVoucher(Request $request)
    {
        $request->validate([
            'code'           => ['required', Rule::unique('vouchers', 'code')->whereNull('deleted_at')],
            'discount_type'  => 'required|in:nominal,percentage',
            'discount_value' => 'required|numeric|min:0',
            'max_uses'       => 'required|integer|min:1',
            'expired_at'     => 'required|date',
            'event_id'       => 'nullable|exists:events,id',
            'ticket_category_id' => 'nullable|exists:ticket_categories,id',
        ]);

        $data = [
            'code'               => strtoupper($request->code),
            'discount_type'      => $request->discount_type,
            'discount_nominal'   => $request->discount_type === 'nominal' ? $request->discount_value : null,
            'discount_percentage'=> $request->discount_type === 'percentage' ? $request->discount_value : null,
            'event_id'           => $request->event_id ?: null,
            'ticket_category_id' => $request->ticket_category_id ?: null,
            'max_uses'           => $request->max_uses,
            'expired_at'         => $request->expired_at,
            'status'             => 'active',
            'used_count'         => 0,
        ];

        Voucher::create($data);

        return redirect()->back()->with('success', 'Voucher berhasil dibuat!');
    }

    public function updateVoucher(Request $request, $id)
    {
        $request->validate([
            'code'           => ['required', Rule::unique('vouchers', 'code')->ignore($id)->whereNull('deleted_at')],
            'discount_type'  => 'required|in:nominal,percentage',
            'discount_value' => 'required|numeric|min:0',
            'max_uses'       => 'required|integer|min:1',
            'expired_at'     => 'required|date',
            'event_id'       => 'nullable|exists:events,id',
            'ticket_category_id' => 'nullable|exists:ticket_categories,id',
        ]);

        $voucher = Voucher::withTrashed()->findOrFail($id);

        $voucher->update([
            'code'               => strtoupper($request->code),
            'discount_type'      => $request->discount_type,
            'discount_nominal'   => $request->discount_type === 'nominal' ? $request->discount_value : null,
            'discount_percentage'=> $request->discount_type === 'percentage' ? $request->discount_value : null,
            'event_id'           => $request->event_id ?: null,
            'ticket_category_id' => $request->ticket_category_id ?: null,
            'max_uses'           => $request->max_uses,
            'expired_at'         => $request->expired_at,
        ]);

        return redirect()->back()->with('success', 'Voucher berhasil diperbarui!');
    }

    public function destroyVoucher($id)
    {
        $voucher = Voucher::withTrashed()->findOrFail($id);

        if ($voucher->trashed()) {
            $voucher->restore();
            return redirect()->back()->with('success', 'Voucher berhasil diaktifkan kembali!');
        } else {
            $voucher->delete();
            return redirect()->back()->with('success', 'Voucher berhasil dinonaktifkan.');
        }
    }

    // --- INSIGHT (DI PALING BAWAH) ---
    public function insight()
    {
        // 1. City Distribution (Top 5)
        $cityData = Transaction::select('city', DB::raw('count(*) as total'))
            ->whereNotNull('city')
            ->where('transaction_status', TransactionStatusEnum::PAID->value)
            ->groupBy('city')
            ->orderBy('total', 'desc')
            ->take(5)
            ->get();

        $city_labels = $cityData->pluck('city')->toArray();
        $city_values = $cityData->pluck('total')->toArray();

        // 2. Source Info (Percentage)
        $totalPaid = Transaction::where('transaction_status', TransactionStatusEnum::PAID->value)->count();
        
        $sources = Transaction::select('source_info as name', DB::raw('count(*) as total'))
            ->whereNotNull('source_info')
            ->where('transaction_status', TransactionStatusEnum::PAID->value)
            ->groupBy('source_info')
            ->get()
            ->map(function ($item) use ($totalPaid) {
                $item->percentage = $totalPaid > 0 
                    ? round(($item->total / $totalPaid) * 100, 1) 
                    : 0;
                return $item;
            });

        return view('admin.insight', [
            'title' => 'Insight',
            'city_labels' => $city_labels,
            'city_values' => $city_values,
            'sources' => $sources
        ]);
    }
    public function emailView()
    {
        $transactions = Transaction::with(['user', 'transactionItems.ticketCategory.event'])->get()->map(function($transaction) {
            $eventName = $transaction->transactionItems->first()?->ticketCategory?->event?->name ?? '-';
            return [
                'invoice_code' => $transaction->invoice_code,
                'event_name' => $eventName,
                'buyer_name' => $transaction->buyer_name ?? ($transaction->user ? $transaction->user->name : '-'),
                'email' => $transaction->user ? $transaction->user->email : null,
                'city' => $transaction->city ?? '-',
                'transaction_status' => $transaction->transaction_status,
            ];
        })->filter(function($transaction) {
            return !empty($transaction['email']); // Only keep transactions with valid user emails
        });
        
        $events = Event::pluck('name')->unique()->values();
        $cities = Transaction::whereNotNull('city')->pluck('city')->unique()->values();
        
        return view('admin.email', [
            'title' => 'Email Broadcast',
            'transactions' => $transactions, 
            'events' => $events, 
            'cities' => $cities
        ]);
    }

    public function sendEmail(Request $request)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
            'emails' => 'required|string', // Comma separated emails
        ]);

        $emails = explode(',', $request->emails);
        // Trim and filter valid emails, ensure uniqueness
        $emails = array_filter(array_map('trim', $emails), function($email) {
            return filter_var($email, FILTER_VALIDATE_EMAIL);
        });
        $emails = array_unique($emails);

        if (empty($emails)) {
            return redirect()->back()->with('error', 'Tidak ada email valid yang dipilih.');
        }

        try {
            foreach ($emails as $email) {
                Mail::to($email)->send(new \App\Mail\CustomAdminMail($request->subject, $request->message));
            }
            
            SystemLog::success('email', "Broadcast email dikirim ke " . count($emails) . " user", 'SYSTEM');

            return redirect()->back()->with('success', 'Email berhasil dikirim ke ' . count($emails) . ' penerima.');
        } catch (\Exception $e) {
            SystemLog::fail('email', "Gagal broadcast email: {$e->getMessage()}", 'SYSTEM');
            return redirect()->back()->with('error', 'Gagal mengirim email: ' . $e->getMessage());
        }
    }
}