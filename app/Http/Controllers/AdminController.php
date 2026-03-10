<?php

namespace App\Http\Controllers;

use Faker\Provider\Base;
use Illuminate\Http\Request;
use App\Models\Admin;;
use App\Models\User;
use App\Models\Event;
use App\Models\Transaction;
use Barryvdh\DomPDF\Facade\Pdf;

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

    // Manage Events
    public function listEvents()
    {
        // Ambil semua events dari database
        // $events = Event::all();
        
        return view('admin.event', [
            'title' => 'Manage Events',
            // 'events' => $events
        ]);
    }

    public function createEvent()
    {
        return view('admin.event-create', [
            'title' => 'Create Event'
        ]);
    }

    public function listCategories()
    {
        return view('admin.category', ['title' => 'Categories']);
    }

    public function transaction()
    {
        return view('admin.transaction.transaction', ['title' => 'Transactions']);
    }

    public function transactionDetail()
    {
        return view('admin.transaction.transactionDetail', ['title' => 'Transaction Detail']);
    }

    public function exportTransactionPDF($invoice_code)
    {
        $transaction = Transaction::with(['user', 'voucher', 'transactionItems.ticketCategory'])
            ->where('invoice_code', $invoice_code)
            ->firstOrFail();

        $pdf = Pdf::loadView('admin.transaction.invoice-pdf', compact('transaction'));
        
        return $pdf->download('invoice_' . $invoice_code . '.pdf');
    }

    public function monitor()
    {
        return view('admin.monitor', ['title' => 'Monitor']);
    }

    public function insight()
    {
        return view('admin.insight', ['title' => 'Insight']);
    }

    public function ticketScan()
    {
        return view('admin.ticketScan', ['title' => 'Ticket Scan']);
    }
}
