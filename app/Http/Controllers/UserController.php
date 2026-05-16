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

    public function onlineEventView(){
        $userId = session('user_id');
        $hasActivePass = false;
        
        $myMoviesIds = [];
        $hasAllAccess = false;

        if ($userId) {
            $activePasses = \App\Models\UserOnlinePass::with('onlineTicket.movies')
                ->where('user_id', $userId)
                ->where('status', 'active')
                ->whereHas('onlineTicket', function($q) {
                    $q->where('access_start_date', '<=', now())
                      ->where('access_end_date', '>=', now());
                })
                ->get();
                
            if ($activePasses->count() > 0) {
                $hasActivePass = true;
                foreach($activePasses as $pass) {
                    if ($pass->onlineTicket->movies->count() == 0) {
                        $hasAllAccess = true;
                    } else {
                        foreach($pass->onlineTicket->movies as $movie) {
                            $myMoviesIds[] = $movie->id;
                        }
                    }
                }
            }
        }
        
        $allMovies = \App\Models\Movie::with('category')->where('is_active', true)->get();
        $allCategories = \App\Models\MovieCategory::orderBy('name')->get();
        
        $myMovies = collect();
        if ($hasActivePass) {
            if ($hasAllAccess) {
                $myMovies = $allMovies;
            } else {
                $myMovies = $allMovies->whereIn('id', $myMoviesIds);
            }
        }

        return view('user.online_event', compact('hasActivePass', 'myMovies', 'allMovies', 'allCategories'));
    }

    public function watchMovie($slug)
    {
        $userId = session('user_id');

        if (!$userId) {
            return redirect()->route('user.login')->with('error', 'Silakan login terlebih dahulu untuk menonton.');
        }

        $movie = \App\Models\Movie::with('category')->where('slug', $slug)->where('is_active', true)->firstOrFail();

        $activePasses = \App\Models\UserOnlinePass::with('onlineTicket.movies')
            ->where('user_id', $userId)
            ->where('status', \App\Enums\UserOnlinePassStatusEnum::ACTIVE->value)
            ->get();

        $hasAccess = false;
        foreach($activePasses as $pass) {
            if ($pass->onlineTicket->movies->count() == 0) {
                $hasAccess = true;
                break;
            } else {
                if ($pass->onlineTicket->movies->contains('id', $movie->id)) {
                    $hasAccess = true;
                    break;
                }
            }
        }

        if (!$hasAccess) {
            return redirect()->route('user.online_event')->with('error', 'Anda belum memiliki akses ke film ini.');
        }

        return view('user.online_event_watch', compact('movie'));
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
            ->with(['tickets.ticketCategory.event', 'transactionItems.ticketCategory', 'voucher'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('user.history', [
            'title' => 'History',
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