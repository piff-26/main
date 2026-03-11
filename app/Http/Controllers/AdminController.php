<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Admin;
use App\Models\User;
use App\Models\Voucher;
use App\Models\Transaction; // Pastikan model ini sudah ada
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
        return view('admin.dashboard', ['title' => 'Dashboard']);
    }

    // --- MANAGE EVENTS ---
    public function listEvents()
    {
        return view('admin.event', ['title' => 'Manage Events']);
    }

    public function createEvent()
    {
        return view('admin.event-create', ['title' => 'Create Event']);
    }

    // --- TRANSACTIONS ---
    public function transaction()
    {
        return view('admin.transaction.transaction', ['title' => 'Transactions']);
    }

    public function transactionDetail()
    {
        return view('admin.transaction.transactionDetail', ['title' => 'Transaction Detail']);
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