@extends('layouts.user')

@section('content')
<div class="container py-5">
    
    <div class="row mb-4">
        <div class="col-md-10 offset-md-1 text-center">
            <h2 class="fw-bold">Lengkapi Data Diri</h2>
            <p class="text-muted">Langkah 2 dari 3: Pastikan data diri Anda benar untuk keperluan E-Ticket.</p>
            
            <div class="alert alert-warning d-inline-block shadow-sm">
                <i class="fas fa-clock me-2"></i> Selesaikan pengisian dan pembayaran sebelum 
                <strong>{{ \Carbon\Carbon::parse($expiredAt)->format('H:i') }} WIB</strong> 
                agar kuota tiket tidak hangus.
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-10 offset-md-1">
            @livewire('checkout-biodata', ['invoice_code' => $invoiceCode])
        </div>
    </div>
    
</div>
@endsection