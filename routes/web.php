<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PaymentController;

Route::get('/', [UserController::class, 'homeView'])->name('user.home');
Route::get('/submit', [UserController::class, 'submitView'])->name('user.submit');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('logged-in')->group(function () {
    Route::get('/checkout/payment/{invoice_code}', [PaymentController::class, 'show'])->name('user.checkout.payment');
});

Route::get('/test', function () {
    return view('user.test');
});

Route::prefix('admin')->group(function(){
    Route::get('/',function(){
        return redirect()->route('admin.dashboard');
    });

    Route::get('login',[AdminController::class,'loginView'])->name('admin.login');
    Route::get('auth/google', [AuthController::class, 'googleAuth'])->name('admin.auth.google');
    Route::get('auth/google/callback', [AuthController::class, 'processLogin'])->name('admin.auth.google.callback');

    
    Route::middleware('admin')->group(function () {
        Route::get('/dashboard',[AdminController::class,'index'])->name('admin.dashboard');
        Route::get('/transaction',function(){
            return view('admin.transaction.transaction');
        })->name('admin.transaction');
        Route::get('/transaction/detail',function(){
            return view('admin.transaction.transactionDetail');
        })->name('admin.transaction.detail');

         Route::get('/event',function(){
            return view('admin.event');
        })->name('admin.event');

        Route::get('/monitor',function(){
            return view('admin.monitor');
        })->name('admin.monitor');
        Route::get('/insight',function(){
            return view('admin.insight');
        })->name('admin.insight');
        Route::get('/ticketscan',function(){
            return view('admin.ticketScan');
        })->name('admin.ticketScan');
    });
});