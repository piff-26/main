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
        $totalRevenue = Transaction::where('transaction_status', 'paid')->sum('total_amount');
        $ticketsSold = Ticket::where('is_canceled', false)->count();
        $totalTickets = TicketCategory::sum('quota');
        $totalTransactions = Transaction::whereIn('transaction_status', ['paid', 'failed', 'expired'])->count();
        $totalCheckin = Ticket::where('is_checked_in', true)->where('is_canceled', false)->count();
        $totalUsers = User::count();
        $paidTransactions = Transaction::where('transaction_status', 'paid')->count();
        $failedTransactions = Transaction::whereIn('transaction_status', ['failed', 'expired'])->count();
        
        $ticketCategories = TicketCategory::selectRaw('name, sold_count as sold')->get();
        
        $checkinByCategory = TicketCategory::leftJoin('tickets', 'ticket_categories.id', '=', 'tickets.ticket_category_id')
            ->selectRaw('ticket_categories.name, 
                SUM(CASE WHEN tickets.is_checked_in = 1 AND tickets.is_canceled = 0 THEN 1 ELSE 0 END) as checked_in,
                SUM(CASE WHEN (tickets.is_checked_in = 0 OR tickets.is_checked_in IS NULL) AND tickets.is_canceled = 0 THEN 1 ELSE 0 END) as not_checked_in')
            ->groupBy('ticket_categories.id', 'ticket_categories.name')
            ->get();
        
        return view('admin.dashboard', compact('totalRevenue', 'ticketsSold', 'totalTickets', 'totalTransactions', 'totalCheckin', 'totalUsers', 'paidTransactions', 'failedTransactions', 'ticketCategories', 'checkinByCategory'));
    }

    public function listEvents()
    {
        $events = Event::with('ticketCategories')->get()->map(function($event) {
            $revenue = Transaction::where('transaction_status', 'paid')
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
                'payment_method' => $transaction->payment_method,
                'transaction_status' => $transaction->transaction_status,
                'created_at' => $transaction->created_at->format('Y-m-d H:i'),
                'paid_at' => $transaction->paid_at ? $transaction->paid_at->format('Y-m-d H:i') : '-'
            ];
        });
        
        $events = Event::pluck('name')->unique()->values();
        $cities = Transaction::whereNotNull('city')->pluck('city')->unique()->values();
        $paymentMethods = Transaction::pluck('payment_method')->unique()->values();
        
        return view('admin.transaction.transaction', compact('transactions', 'events', 'cities', 'paymentMethods'));
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
        $transaction = Transaction::with(['user', 'voucher', 'transactionItems.ticketCategory'])
            ->where('invoice_code', $invoice_code)
            ->firstOrFail();

        $pdf = Pdf::loadView('admin.transaction.invoice-pdf', compact('transaction'));
        
        return $pdf->download('invoice_' . $invoice_code . '.pdf');
    }

    // --- MONITOR & SCAN ---
    public function monitor()
    {
        return view('admin.monitor', ['title' => 'Monitor']);
    }

    public function ticketScan()
    {
        return view('admin.ticketScan', ['title' => 'Ticket Scan']);
    }

    public function storeEvent(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'event_date' => 'required|date|after_or_equal:today',
                'start_time' => 'required',
                'end_time' => 'required|after:start_time',
                'location' => 'required|string|max:255',
            ]);

            Event::create([
                'name' => $request->name,
                'slug' => \Str::slug($request->name),
                'event_date' => $request->event_date,
                'start_time' => $request->start_time,
                'end_time' => $request->end_time,
                'location' => $request->location,
            ]);

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
                'name' => 'required|string|max:255',
                'event_date' => 'required|date|after_or_equal:today',
                'start_time' => 'required',
                'end_time' => 'required|after:start_time',
                'location' => 'required|string|max:255',
            ]);

            $event->update([
                'name' => $request->name,
                'slug' => \Str::slug($request->name),
                'event_date' => $request->event_date,
                'start_time' => $request->start_time,
                'end_time' => $request->end_time,
                'location' => $request->location,
            ]);

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

    public function cancelTransaction($invoice_code)
    {
        try {
            $transaction = Transaction::where('invoice_code', $invoice_code)->firstOrFail();
            
            // Prevent canceling already completed or failed transactions
            if (in_array($transaction->transaction_status, ['expired', 'failed'])) {
                return response()->json(['success' => false, 'message' => 'Transaction already cancelled or failed'], 422);
            }
            
            if ($transaction->transaction_status === 'paid') {
                // Refund: Update transaction and cancel tickets
                $transaction->update(['transaction_status' => 'expired']);
                $transaction->tickets()->update(['is_canceled' => true, 'canceled_at' => now()]);
                
                // Decrease sold_count for each ticket category
                foreach ($transaction->tickets as $ticket) {
                    $ticket->ticketCategory->decrement('sold_count');
                }
            } else {
                // Cancel draft: Just update status
                $transaction->update(['transaction_status' => 'expired']);
            }
            
            return response()->json(['success' => true, 'message' => 'Transaction cancelled successfully']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 422);
        }
    }

    // --- MANAGE VOUCHERS ---
    public function listVouchers()
    {
        // Mengambil semua voucher termasuk yang di-soft delete
        $vouchers = Voucher::withTrashed()->with(['event', 'ticketCategory'])->get();

        return view('admin.manageVouchers', [
            'title' => 'Manage Vouchers',
            'vouchers' => $vouchers
        ]);
    }

    public function storeVoucher(Request $request)
    {
        $request->validate([
            'code' => [
                'required',
                Rule::unique('vouchers', 'code')->whereNull('deleted_at')
            ],
            'discount_type' => 'required|in:nominal,percentage',
            'discount_value' => 'required|numeric',
            'max_uses' => 'nullable|integer',
            'expired_at' => 'required|date',
        ]);

        $data = [
            'code' => $request->code,
            'discount_type' => $request->discount_type,
            'max_uses' => $request->max_uses ?? 0,
            'expired_at' => $request->expired_at,
            'status' => 'active',
            'used_count' => 0,
        ];

        if ($request->discount_type == 'nominal') {
            $data['discount_nominal'] = $request->discount_value;
            $data['discount_percentage'] = 0;
        } else {
            $data['discount_percentage'] = $request->discount_value;
            $data['discount_nominal'] = 0;
        }

        Voucher::create($data);

        return redirect()->back()->with('success', 'Voucher berhasil dibuat!');
    }

    public function updateVoucher(Request $request, $id)
    {
        $request->validate([
            'code' => ['required', Rule::unique('vouchers', 'code')->ignore($id)->whereNull('deleted_at')],
            'discount_type' => 'required|in:nominal,percentage',
            'discount_value' => 'required|numeric',
            'max_uses' => 'nullable|integer',
            'expired_at' => 'required|date',
        ]);

        $voucher = Voucher::withTrashed()->findOrFail($id);
        
        $data = [
            'code' => $request->code,
            'discount_type' => $request->discount_type,
            'max_uses' => $request->max_uses ?? 0,
            'expired_at' => $request->expired_at,
        ];

        if ($request->discount_type == 'nominal') {
            $data['discount_nominal'] = $request->discount_value;
            $data['discount_percentage'] = 0;
        } else {
            $data['discount_percentage'] = $request->discount_value;
            $data['discount_nominal'] = 0;
        }

        $voucher->update($data);
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
            ->where('transaction_status', 'paid')
            ->groupBy('city')
            ->orderBy('total', 'desc')
            ->take(5)
            ->get();

        $city_labels = $cityData->pluck('city')->toArray();
        $city_values = $cityData->pluck('total')->toArray();

        // 2. Source Info (Percentage)
        $totalPaid = Transaction::where('transaction_status', 'paid')->count();
        
        $sources = Transaction::select('source_info as name', DB::raw('count(*) as total'))
            ->whereNotNull('source_info')
            ->where('transaction_status', 'paid')
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
}