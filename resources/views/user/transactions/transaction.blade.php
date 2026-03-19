@extends('layouts.user')

@section('content')
    <div class="min-h-screen bg-black py-36 px-4">

        <div class="max-w-3xl mx-auto mb-8 text-center">
            <h2 class="text-3xl font-bold text-white">TRANSAKSI</h2>
            <p class="text-gray-400 mt-2">Pastikan data diri Anda benar untuk keperluan E-Ticket.</p>

            @if(session('warning'))
                <div class="inline-flex items-center gap-2 bg-orange-400/20 border border-orange-400/50 text-orange-300 rounded-xl px-4 py-3 mt-4 text-sm">
                    <i class="fas fa-exclamation-triangle"></i>
                    {{ session('warning') }}
                </div>
            @endif

            <div
                class="inline-flex items-center gap-2 bg-yellow-400/20 border border-yellow-400/50 text-yellow-300 rounded-xl px-4 py-3 mt-4 text-sm">
                <i class="fas fa-clock"></i>
                Selesaikan pengisian dan pembayaran sebelum
                <strong>{{ \Carbon\Carbon::parse($expiredAt)->setTimezone('Asia/Jakarta')->format('H:i') }} WIB</strong>
                agar kuota tiket tidak hangus.
            </div>
        </div>

        <div class="max-w-3xl mx-auto">
            @livewire('checkout-biodata', ['invoice_code' => $invoiceCode])
        </div>

    </div>
@endsection
