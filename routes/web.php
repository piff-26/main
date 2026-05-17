<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\TransactionHistoryController;
use App\Http\Controllers\TransactionController;

// Route::get('/cektiket',function(){
//     return view('pdf.tickets.designs.ticket');
// });

// Route::get('/cekbundle',function(){
//     return view('pdf.tickets.bundle');
// });


Route::get('/', [UserController::class, 'homeView'])->name('user.home');
Route::get('/ticket', [UserController::class, 'ticketView'])->name('user.ticket');
Route::get('/online-event', [UserController::class, 'onlineEventView'])->name('user.online_event');
Route::get('/online-event/{slug}', [UserController::class, 'watchMovie'])->name('user.online_event.watch');
Route::get('/submit', [UserController::class, 'submitView'])->name('user.submit');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/login', [AuthController::class, 'userLoginView'])->name('user.login');
Route::get('/auth/google', [AuthController::class, 'userGoogleAuth'])->name('user.auth.google');
Route::get('/auth/google/callback', [AuthController::class, 'userProcessLogin'])->name('user.auth.google.callback');

Route::get('/checkout/{event_slug}', [TransactionController::class, 'step1'])->name('checkout.step1');
Route::get('/online-pass/{slug}', [\App\Http\Controllers\OnlinePassCheckoutController::class, 'step1'])->name('online-pass.step1');

Route::prefix('admin')->group(function(){
    Route::get('/',function(){
        return redirect()->route('admin.login');
    });

    Route::get('login',[AdminController::class,'loginView'])->name('admin.login');
    Route::get('auth/google', [AuthController::class, 'googleAuth'])->name('admin.auth.google');
    Route::get('auth/google/callback', [AuthController::class, 'processLogin'])->name('admin.auth.google.callback');

    
    Route::middleware('admin')->group(function () {
        // Dashboard — semua admin
        Route::get('/dashboard',[AdminController::class,'index'])->name('admin.dashboard');

        // kalau ganti middleware, jangan lupa ganti sidebar juga
        Route::middleware('admin.division:bph,sc,acara,sekkon,it')->group(function () {
            // transaction
            Route::get('/transaction',[AdminController::class,'transaction'])->name('admin.transaction');
            Route::get('/transaction/detail/{invoice_code?}',[AdminController::class,'transactionDetail'])->name('admin.transaction.detail');
            Route::get('/transaction/{invoice_code}/export-pdf',[AdminController::class,'exportTransactionPDF'])->name('admin.transaction.export-pdf');

            // event
            Route::get('/event',[AdminController::class, 'listEvents'])->name('admin.event');
            Route::get('/event/{id}/detail',[AdminController::class, 'eventDetail'])->name('admin.event.detail');
            Route::get('/event/{id}/export-excel',[AdminController::class, 'exportEventTicketsExcel'])->name('admin.event.export-excel');
            Route::post('/event',[AdminController::class, 'storeEvent'])->name('admin.event.store');
            Route::put('/event/{id}',[AdminController::class, 'updateEvent'])->name('admin.event.update');
            Route::delete('/event/{id}',[AdminController::class, 'deleteEvent'])->name('admin.event.delete');

            // category
            Route::get('/category',[AdminController::class, 'listCategories'])->name('admin.category');
            Route::post('/category',[AdminController::class, 'storeCategory'])->name('admin.category.store');
            Route::put('/category/{id}',[AdminController::class, 'updateCategory'])->name('admin.category.update');
            Route::delete('/category/{id}',[AdminController::class, 'deleteCategory'])->name('admin.category.delete');
            Route::patch('/category/{id}/toggle',[AdminController::class, 'toggleCategory'])->name('admin.category.toggle');

            // transaction
            Route::delete('/transaction/{invoice_code}',[AdminController::class, 'cancelTransaction'])->name('admin.transaction.cancel');
            Route::post('/transaction/{invoice_code}/validate',[AdminController::class, 'validatePayment'])->name('admin.transaction.validate');
            Route::post('/transaction/{invoice_code}/reject',[AdminController::class, 'rejectPayment'])->name('admin.transaction.reject');

            // monitor
            Route::get('/monitor',[AdminController::class,'monitor'])->name('admin.monitor');

            // insight
            Route::get('/insight',[AdminController::class,'insight'])->name('admin.insight');

            // ticket scan
            Route::get('/ticketscan',[AdminController::class,'ticketScan'])->name('admin.ticketScan');
            Route::get('/checkin/{ticket_code}', [TicketController::class, 'processCheckIn'])->name('admin.ticket.checkin');
            Route::get('/ticket/lookup/{ticket_code}', [TicketController::class, 'lookupTicket'])->name('admin.ticket.lookup');

            // voucher
            Route::get('/managevouchers', [AdminController::class, 'listVouchers'])->name('admin.manageVouchers');
            Route::post('/managevouchers/store', [AdminController::class, 'storeVoucher'])->name('admin.voucher.store');
            Route::delete('/managevouchers/{id}', [AdminController::class, 'destroyVoucher'])->name('admin.voucher.destroy');
            Route::put('/managevouchers/{id}', [AdminController::class, 'updateVoucher'])->name('admin.voucher.update');

            // Online Event Portal - Movie Category
            Route::get('/movie-category', [\App\Http\Controllers\MovieCategoryController::class, 'index'])->name('admin.movie_category');
            Route::post('/movie-category', [\App\Http\Controllers\MovieCategoryController::class, 'store'])->name('admin.movie_category.store');
            Route::put('/movie-category/{id}', [\App\Http\Controllers\MovieCategoryController::class, 'update'])->name('admin.movie_category.update');
            Route::delete('/movie-category/{id}', [\App\Http\Controllers\MovieCategoryController::class, 'destroy'])->name('admin.movie_category.destroy');

            // Online Event Portal - Movie
            Route::get('/movie', [\App\Http\Controllers\MovieController::class, 'index'])->name('admin.movie');
            Route::post('/movie', [\App\Http\Controllers\MovieController::class, 'store'])->name('admin.movie.store');
            Route::put('/movie/{id}', [\App\Http\Controllers\MovieController::class, 'update'])->name('admin.movie.update');
            Route::delete('/movie/{id}', [\App\Http\Controllers\MovieController::class, 'destroy'])->name('admin.movie.destroy');
            Route::patch('/movie/{id}/toggle', [\App\Http\Controllers\MovieController::class, 'toggle'])->name('admin.movie.toggle');

            // Online Event Portal - Online Ticket
            Route::get('/online-ticket', [\App\Http\Controllers\OnlineTicketController::class, 'index'])->name('admin.online_ticket');
            Route::post('/online-ticket', [\App\Http\Controllers\OnlineTicketController::class, 'store'])->name('admin.online_ticket.store');
            Route::put('/online-ticket/{id}', [\App\Http\Controllers\OnlineTicketController::class, 'update'])->name('admin.online_ticket.update');
            Route::delete('/online-ticket/{id}', [\App\Http\Controllers\OnlineTicketController::class, 'destroy'])->name('admin.online_ticket.destroy');
            Route::patch('/online-ticket/{id}/toggle', [\App\Http\Controllers\OnlineTicketController::class, 'toggle'])->name('admin.online_ticket.toggle');

            // Online Event Portal - User Online Pass
            Route::get('/user-online-pass', [\App\Http\Controllers\UserOnlinePassController::class, 'index'])->name('admin.user_online_pass');
            Route::patch('/user-online-pass/{id}/status', [\App\Http\Controllers\UserOnlinePassController::class, 'updateStatus'])->name('admin.user_online_pass.update_status');
        });

        Route::middleware('admin.division:it,bph,sc')->group(function () {
            // system log
            Route::get('/log', [AdminController::class, 'systemLog'])->name('admin.log');
        });

        Route::middleware('admin.division:it')->group(function () {
            // email broadcast
            Route::get('/email', [AdminController::class, 'emailView'])->name('admin.email');
            Route::post('/email/send', [AdminController::class, 'sendEmail'])->name('admin.email.send');
        });
    });
    
});

Route::middleware('auth')->group(function () {
    
    // menu user (Riwayat & Tiket)
    Route::get('/history', [UserController::class, 'myTransactions'])->name('user.transactions-history');

    Route::get('/transaction/{invoice_code}/download', [TransactionHistoryController::class, 'downloadETicket'])->name('ticket.download');

    Route::prefix('checkout')->name('checkout.')->group(function () {
        // Isi Biodata & Pembayaran
        Route::get('/transaction/{invoice_code}', [TransactionController::class, 'step2'])->name('step2');
        Route::post('/{event_slug}/store', [TransactionController::class, 'storeStep1'])->name('storeStep1');
    });

    Route::prefix('online-pass')->name('online-pass.')->group(function () {
        Route::get('/checkout/{invoice_code}', [\App\Http\Controllers\OnlinePassCheckoutController::class, 'step2'])->name('checkout.step2');
        Route::post('/{slug}/store', [\App\Http\Controllers\OnlinePassCheckoutController::class, 'storeStep1'])->name('checkout.storeStep1');
    });
});

Route::get('/admin/ticket/lookup/{ticket_code}', [TicketController::class, 'lookupTicket'])->name('admin.ticket.lookup');

Route::get('/test', function () {
    return view('user.test');
});