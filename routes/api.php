<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\TicketController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Route::post('/midtrans/callback', [PaymentController::class, 'callback']);

Route::get('/checkin/{ticket_code}', [TicketController::class, 'processCheckIn'])->name('api.checkin');
