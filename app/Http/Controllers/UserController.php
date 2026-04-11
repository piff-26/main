<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Ticket;
use App\Models\Transaction;
use App\Models\Event;
use Illuminate\Http\Request;
use App\Enums\TransactionStatusEnum;

class UserController extends BaseController
{
    public function __construct()
    {
        parent::__construct(new User());
    }
    public function homeView()
    {
        return view('user.home', ['title' => 'Home']);
    }

    public function registerUserView()
    {
        return view('user.regist.anggota');
    }

    public function submitView(){
        return view('user.submit');
    }


    public function myTransactions()
    {
        $userId = session('user_id');

        if (!$userId) {
            return redirect()->route('user.home')->with('error', 'Silakan login terlebih dahulu.');
        }

        $transactions = Transaction::where('user_id', $userId)
            ->whereIn('transaction_status', [
                TransactionStatusEnum::PAID->value,
                TransactionStatusEnum::PENDING->value,
                TransactionStatusEnum::FAILED->value,
            ])
            ->with(['tickets.ticketCategory.event', 'transactionItems.ticketCategory'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('user.history', [
            'title' => 'Riwayat Transaksi & Tiket',
            'transactions' => $transactions
        ]);
    }

    public function ticketView()
{
    // Ambil semua event, urutkan dari yang terbaru
    $events = Event::orderBy('created_at', 'desc')->get();

    return view('user.ticket', [
        'title' => 'Beli Tiket Event',
        'events' => $events
    ]);
}
}