<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\TransactionHistoryController;
use App\Http\Controllers\TransactionController;

Route::get('/', [UserController::class, 'homeView'])->name('user.home');
Route::get('/ticket', [UserController::class, 'ticketView'])->name('user.ticket');
Route::get('/submit', [UserController::class, 'submitView'])->name('user.submit');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/login', [AuthController::class, 'userLoginView'])->name('user.login');
Route::get('/auth/google', [AuthController::class, 'userGoogleAuth'])->name('user.auth.google');
Route::get('/auth/google/callback', [AuthController::class, 'userProcessLogin'])->name('user.auth.google.callback');

Route::prefix('admin')->group(function(){
    Route::get('/',function(){
        return redirect()->route('admin.login');
    });

    Route::get('login',[AdminController::class,'loginView'])->name('admin.login');
    Route::get('auth/google', [AuthController::class, 'googleAuth'])->name('admin.auth.google');
    Route::get('auth/google/callback', [AuthController::class, 'processLogin'])->name('admin.auth.google.callback');

    
    Route::middleware('admin')->group(function () {
        Route::get('/dashboard',[AdminController::class,'index'])->name('admin.dashboard');
        Route::get('/transaction',[AdminController::class,'transaction'])->name('admin.transaction');
        Route::get('/transaction/detail/{invoice_code?}',[AdminController::class,'transactionDetail'])->name('admin.transaction.detail');
        Route::get('/transaction/{invoice_code}/export-pdf',[AdminController::class,'exportTransactionPDF'])->name('admin.transaction.export-pdf');
        Route::get('/event',[AdminController::class, 'listEvents'])->name('admin.event');
        Route::post('/event',[AdminController::class, 'storeEvent'])->name('admin.event.store');
        Route::put('/event/{id}',[AdminController::class, 'updateEvent'])->name('admin.event.update');
        Route::delete('/event/{id}',[AdminController::class, 'deleteEvent'])->name('admin.event.delete');
        
        Route::get('/category',[AdminController::class, 'listCategories'])->name('admin.category');
        Route::post('/category',[AdminController::class, 'storeCategory'])->name('admin.category.store');
        Route::put('/category/{id}',[AdminController::class, 'updateCategory'])->name('admin.category.update');
        Route::delete('/category/{id}',[AdminController::class, 'deleteCategory'])->name('admin.category.delete');
        
        Route::delete('/transaction/{invoice_code}',[AdminController::class, 'cancelTransaction'])->name('admin.transaction.cancel');
        Route::post('/transaction/{invoice_code}/validate',[AdminController::class, 'validatePayment'])->name('admin.transaction.validate');
        Route::post('/transaction/{invoice_code}/reject',[AdminController::class, 'rejectPayment'])->name('admin.transaction.reject');
        Route::get('/monitor',[AdminController::class,'monitor'])->name('admin.monitor');
        Route::get('/insight',[AdminController::class,'insight'])->name('admin.insight');
        Route::get('/ticketscan',[AdminController::class,'ticketScan'])->name('admin.ticketScan');
        Route::get('/checkin/{ticket_code}', [TicketController::class, 'processCheckIn'])->name('admin.ticket.checkin');
        Route::get('/ticket/lookup/{ticket_code}', [TicketController::class, 'lookupTicket'])->name('admin.ticket.lookup');

        Route::get('/managevouchers', [AdminController::class, 'listVouchers'])->name('admin.manageVouchers');
        // Route baru untuk handle Create dan Delete Voucher
        Route::post('/managevouchers/store', [AdminController::class, 'storeVoucher'])->name('admin.voucher.store');
        Route::delete('/managevouchers/{id}', [AdminController::class, 'destroyVoucher'])->name('admin.voucher.destroy');
        Route::put('/managevouchers/{id}', [AdminController::class, 'updateVoucher'])->name('admin.voucher.update');
    });
    
});

Route::middleware('auth')->group(function () {
    
    // menu user (Riwayat & Tiket)
    Route::get('/history', [UserController::class, 'myTransactions'])->name('user.transactions-history');
    

    Route::get('/transaction/{invoice_code}/download', [TransactionHistoryController::class, 'downloadETicket'])->name('ticket.download');


    Route::prefix('checkout')->name('checkout.')->group(function () {
        
        // Pilih Event & Kategori Tiket
        Route::get('/{event_slug}', [TransactionController::class, 'step1'])->name('step1');
        Route::post('/{event_slug}/store', [TransactionController::class, 'storeStep1'])->name('storeStep1');

        // Isi Biodata & Pembayaran
        Route::get('/transaction/{invoice_code}', [TransactionController::class, 'step2'])->name('step2');
        
    });
});

Route::get('/admin/ticket/lookup/{ticket_code}', [TicketController::class, 'lookupTicket'])->name('admin.ticket.lookup');

Route::get('/test', function () {
    return view('user.test');
});