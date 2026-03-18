@extends('layouts.user')

@section('content')
<div class="bg-gray-50 min-h-screen py-10">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="text-center mb-8">
            <h2 class="text-3xl font-extrabold text-gray-900">Konfirmasi Pesanan</h2>
            <p class="mt-2 text-sm text-gray-600">Langkah 3 dari 4: Review pesanan Anda dan terapkan voucher (jika ada) sebelum membayar.</p>
            
            <div class="mt-4 inline-flex items-center bg-yellow-50 border border-yellow-200 text-yellow-800 text-sm font-medium px-4 py-3 rounded-lg shadow-sm">
                <svg class="w-5 h-5 mr-2 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                Selesaikan pembayaran sebelum 
                <strong class="ml-1">{{ \Carbon\Carbon::parse($transaction->expired_at)->format('H:i') }} WIB</strong>
            </div>
        </div>

        @livewire('checkout-confirm', ['invoice_code' => $invoiceCode])
        
    </div>
</div>
@endsection